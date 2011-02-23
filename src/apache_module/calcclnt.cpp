#include <string.h>
#include "soapH.h"
#include "ledger.nsmap"

#define FIELD_COUNT 1

void ns1__ServiceArray::print() 
{
printf("size of array = %d\n",__size);
     for (int i = 0; i < __size; i++) 
     cout << __ptr[i].ID << ": " << __ptr[i].ID << endl; 
} 

ns1__ServiceArray::ns1__ServiceArray() 
{
	__size = 0; 
	__ptr = 0; 
}

ns1__ServiceArray::ns1__ServiceArray(int count) 
{
	__size = count; 
	__ptr = (ns1__SOAPService*)soap_malloc(soap, count*sizeof(ns1__SOAPService)); 
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

int main(int argc, char** argv)
{ 
  struct soap *soap = soap_new();
//  soap_set_recv_logfile(soap, "/home/gr/resv.log"); // append all messages received in /logs/recv/service12.log 
//  soap_set_sent_logfile(soap, "/home/gr/sent.log"); // append all messages sent in /logs/sent/service12.log 
  
//  struct soap soap; 
  ns1__ServiceArray *result = soap_new_ns1__ServiceArray(soap,-1);
//  ns1__ServiceArray **ptr = &result;
//	result->__ptr = soap_new_ns1__SOAPService(soap, 20);
//	result->__size = 20;
  //          cout << "<<" << endl;
    //          for(int i=0; i<result->__size;i++)
      //                {
        //                              result->__ptr[i].ID = i;
          //  }
//  const char *endpoint = "www.xmethods.net:80/soap/servlet/rpcrouter"; 
//  const char *action = "urn:xmethodsServicesManager#getAllSOAPServices"; 
  soap_init(soap); 
  int a=4, b=9;//, result;
  char **res;
  char *rc = "/home/gr/devel/ananas-engine-qt/applications/inventory/inventory.rc";
  char *journal_name = "DocJournal.Журнал прихода";
  char *host = "http://localhost/ledger";
  int count;
  time_t dateBegin = 0;
  time_t dateEnd = time(0);
  struct ns1__ListDoc doc;// = new (struct ns1__ListDoc); 
  soap->userid="123123";
	  //result->print();
 // soap_call_ns1__login(soap,host,"",host,host,&count);
  //if(!soap_call_ns1__getAllSOAPServices(soap, host, "", result))
  if(!soap_call_ns1__getAllSOAPServices(soap, host, "", result))
  //if(ns__getAllSOAPServices(soap, result))
  {
	  printf("123\n");
	  
	  result->print();
  }
  else
  {
	  
	printf("bad\n");	  
      soap_print_fault(soap, stdout);
  }
  soap_end(soap);
  free(soap);
  return 0;
//	doc.doc = new (struct ns1__Doc)[50];
/*	int tmp=0;
				do
				{
	    				doc.doc[tmp].fields  = new (struct ns1__Field) [FIELD_COUNT];
					for(int i=0; i<FIELD_COUNT; i++)
					{
						doc.doc[tmp].fields[i].fieldName = new char[254];
						doc.doc[tmp].fields[i].value = new char[254];
					}
					tmp++;		
				}
				while(tmp<5);
				*/
//	char **doc;
/*    if(soap_call_ns1__login(soap, host, "", "wasya", "pupkin", &result) == 0)
    {
      printf("login is `%d'\n",result);
    }
    else
    {
      soap_print_fault(soap, stdout);
    }
    
    if(soap_call_ns1__getDocumentList(soap, host, "",rc,journal_name, 0, dateBegin, dateEnd, &doc )==0)
    {
	    printf("document count is `%d'\n",doc.__size);
	for(int i=0; i<doc.__size; i++)
	{
		printf(">>>document %d\n",i);
//		for(int j=0; j<FIELD_COUNT; j++)
		{
			printf("document fieldName is `%s', value is `%s'\n",doc.__ptr[i].fieldName,doc.__ptr[i].value);
//			printf("document fieldName is `%s', value is `%s'\n",doc.__ptr[i].__ptr[j].fieldName,doc.__ptr[i].__ptr[j].value);
		}
	}	
    }
    else
    {
	    soap_print_fault(soap, stdout);
    }*/
  return 0;
}

