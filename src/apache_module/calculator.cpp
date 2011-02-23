/** Demo of a calculator gsoap service implemented as a shared library that can be loaded from Apache Http Server.
 * @author Christian Aberger (http://www.aberger.at)
 * @file calculator.cpp
 */

#include <float.h>
#include <string.h>
#include <qapplication.h>
#include <qdatetime.h>
#include <qvaluelist.h>
#include "soapH.h"

#include "stdsoap2.h"
#include "ledger.nsmap" // link the namespace
#include "apache_gsoap.h"
#include "ananas.h"
//#include "aservice.h"

#define FIELD_COUNT 1 // кол-во полей документа

IMPLEMENT_GSOAP_SERVER() 


void ns1__ServiceArray::print() 
{
printf("size of array = %d\n",__size);
     for (int i = 0; i < __size; i++) 
     cout << i << ": " << __ptr[i].ID << endl; 
}

ns1__ServiceArray::ns1__ServiceArray() 
{
	__ptr = 0; 
	__size = 0; 
}

ns1__ServiceArray::ns1__ServiceArray(int count) 
{
	__ptr = (ns1__SOAPService*)soap_malloc(soap, count*sizeof(ns1__SOAPService)); 
	__size = count; 
}

ns1__ServiceArray::~ns1__ServiceArray() 
{
	soap_unlink(soap, this); // not required, but just to make sure if someone calls delete on this
}

ns1__SOAPService::ns1__SOAPService()
{
	ID=0;
}


ns1__SOAPService::~ns1__SOAPService()
{
	soap_unlink(soap, this); // not required, but just to make sure if someone calls delete on this
}





int ns1__login(struct soap *soap, xsd__string log_name, xsd__string login, xsd__string password, int *result)
{
	QString lg = login;
	QString pw = password;
	QString l_name = log_name;
	//if(!soap->userid) return 401;
//	if(*result)
//	*result = 100;
	*result = 1;//aService::ananas_remote_login(lg, pw);
	{
		aTests::print2log(l_name,"login","OK",QString("'%1' login succesfull").arg(lg));
	}
//	else
	{
		aTests::print2log(l_name,"login","ERROR",QString("'%1' login failed").arg(lg));
		return 401;
	}
	return SOAP_OK;
	
}

int
ns1__getDocumentList(	struct soap *soap, 
			xsd__string log_name,
			xsd__string rc_file, 
			xsd__string journal_name, 
			int kindOfDoc,
			time_t dateBegin, 
			time_t dateEnd,
			struct ns1__ListDoc *_result )
{
	QString rc = rc_file;
	QString jname = journal_name;
	QString l_name = log_name;
	aDatabase adb;
	aDocJournal *journal;
//	struct ns1__Doc doc;
//	struct ns1__Field field;
	int argc =0;
	char **argv = NULL;
	QApplication *app = new QApplication(argc,argv,QApplication::Tty);
	if(adb.init(rc_file))
	{
		aTests::print2log(l_name,"init","OK","init database");
//		aService::writeToLog(s);
		journal = new aDocJournal(jname,&adb);
		if(journal!=NULL)
		{
			aTests::print2log(l_name,"init","OK","init journal");
			QValueList<Q_ULLONG> lst;
			QDateTime dateFrom;
			QDateTime dateTo;
			dateFrom.setTime_t(dateBegin);
			dateTo.setTime_t(dateEnd);
			aTests::print2log(l_name,"select documents","NOTE","try select document from journal");
			aTests::print2log(l_name,"select documents","NOTE",QString("date begin %1").arg(dateFrom.toString()));
			aTests::print2log(l_name,"select documents","NOTE",QString("date end %1").arg(dateTo.toString()));
			journal->selectionFilter(dateFrom, dateTo);
			int tmp = 0;
			aSQLTable *t= journal->table();
			t->select();

			unsigned long  ln = 0;
			if(t->first())
			{
				aTests::print2log(l_name,"select documents","OK","journal is not empty ");
				do
				{
					//ln+= sizeof(struct ns1__Field);
					tmp++;		
				}
				while(t->next());
			}
			else
			{
				aTests::print2log(l_name,"select documents","WARNING","journal is empty");
			}
			aTests::print2log(l_name,"select documents","NOTE",QString("documents count = %1").arg(tmp));
			
    			unsigned short c = tmp;
//			struct ns1__Doc *doc;
//			result = new (struct ns1__ListDoc*);
//			(result_)->__ptr = new (struct ns1__Doc) [c];
//			(result_)->__size = c;
//			(result_)->__ptr = new (struct ns1__Field) [c];
			(*_result).__ptr = (struct ns1__Doc *)soap_malloc(soap,sizeof(struct ns1__Doc)*c);
			aTests::print2log(l_name,"memory alloc","NOTE",QString("for ns1__Doc"));
			(*_result).__size = c;
			int field_count = t->count();
			aTests::print2log(l_name,"field count","NOTE",QString("%1").arg(field_count));
			//tmp=0;
			if(t->first())
			{
				for(int i=0; i<c; i++)
				{
					(*_result).__ptr[i].__ptr = (struct ns1__Field *)soap_malloc(soap,sizeof(struct ns1__Field)*field_count);				
					aTests::print2log(l_name,"memory alloc","NOTE",QString("for ns1__Field %1").arg(i));
					(*_result).__ptr[i].__size = field_count;
					QString val,fname;
					for(int j=0; j<field_count; j++)
					{
						if(!t->value(j).toString().isNull() )
						{
							val = t->value(j).toString();
						}
						else
						{
							val = "";
						}
						fname = t->fieldName(j);
						aTests::print2log(l_name,"values","NOTE",QString("[%1]=%2").arg(fname).arg(val));
						
						int lengthVal = val.length()+1;
						int lengthFname = fname.length()+1;
						
						(*_result).__ptr[i].__ptr[j].val = (char *)soap_malloc(soap,sizeof(char)*lengthVal);
						(*_result).__ptr[i].__ptr[j].fieldName = (char *)soap_malloc(soap,sizeof(char)*lengthFname);
						strcpy((*_result).__ptr[i].__ptr[j].fieldName,(const char*)fname);
						if(lengthVal>1)
						{
							strncpy((*_result).__ptr[i].__ptr[j].val,(const char*)(val),lengthVal);
							(*_result).__ptr[i].__ptr[j].val[lengthVal-1]='\0';
						}
						else
						{
							aTests::print2log(l_name,"values","WARNING",QString("%1 is empty").arg(fname));

							(*_result).__ptr[i].__ptr[j].val[0]='\0';
						}
						//strcpy((*_result).__ptr[i].__ptr[j].value,"ddtest2");
					}
					if(!t->next()) break;
				}
			}
		}
		else
		{
			aTests::print2log(l_name,"init","ERROR","init journal");
//			aService::writeToLog(s);		
		}
	}
	else
	{
		aTests::print2log(l_name,"init","ERROR","init database");
//		aService::writeToLog(s);		
		
	}
	delete app;
	return SOAP_OK;
}
int ns1__getDoc(struct soap *soap, xsd__string rc_file, xsd__string journal_name, int id, struct ns1__Doc *result)
{
	return SOAP_OK;
}

int ns1__getAllSOAPServices(struct soap *soap, ns1__ServiceArray *ret)
{

	int count = 11;
//  	ns1__ServiceArray *ret1 = soap_new_ns1__ServiceArray(soap,-1);
	(ret)->__ptr = (ns1__SOAPService*)soap_malloc(soap, count*sizeof(ns1__SOAPService)); 
	(ret)->__size = 11;
//	cout << "<<" << endl;
	for(int i=0; i<(ret)->__size;i++)
	{
		(ret)->__ptr[i].ID = i;
	}
//	ret = ret1;
	return SOAP_OK;
	
}
