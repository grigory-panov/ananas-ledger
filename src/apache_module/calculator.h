//gsoap ns1 service name:	ledger	
//gsoap ns1 service location:	http://localhost/ledger
//gsoap ns1 service namespace:	ledger
//gsoap ns1 schema namespace:	ledger
//gsap ns1 service executable: gsoap

class ns1__SOAPService 
{ 
	public:
        	int ID;
		struct soap *soap;
		ns1__SOAPService();
//	ns1__SOAPService(int count);
		~ns1__SOAPService();
/*	     char *name; 
         char *owner; 
	 char *description; 
         char *homepage; 
	 char *endpoint; 
	 char *SOAPAction; 
	 char *methodNamespaceURI; 
	 char *serviceStatus; 
char *methodName; 
char *dateCreated; 
char *downloadURL; 
char *wsdlURL; 
char *instructions; 
char *contactEmail; 
char *serverImplementation; */
};

class ns1__ServiceArray 
{ 
	public: 
		ns1__SOAPService *__ptr; // points to array elements 
	        int __size; // number of elements pointed to 
	  	ns1__ServiceArray();
		struct soap *soap;
		ns1__ServiceArray(int);
		~ns1__ServiceArray();
          	void print(); 
}; 

int ns1__getAllSOAPServices(ns1__ServiceArray *return_); 

typedef char* xsd__string;

struct ns1__Field
{
	xsd__string fieldName;
	xsd__string val;
//	xsd__string fieldName2;
//	int fieldName;
//	int value;
	struct soap *soap;
};

struct ns1__Doc
{
	struct ns1__Field *__ptr; 
	int __size;
	struct soap *soap;
};

struct ns1__ListDoc
{
	struct ns1__Doc *__ptr;
	int __size;
	struct soap *soap;
};


int ns1__login(	xsd__string param1, /*log file name */
		xsd__string param2, /*login*/ 
		xsd__string param3, /*password*/
		int *result);

int ns1__getDocumentList(
		xsd__string param1, /*log file name*/
		xsd__string param2, /*path to rc file*/
		xsd__string param3, /*full journal name*/
		int param4, /*kind of doc*/
		time_t param5, /*date start*/
		time_t param6, /*date end */
		struct ns1__ListDoc *result_ );

int ns1__getDoc(xsd__string rcFile, 
		xsd__string journal_name,
		int id,
		struct ns1__Doc *result);

