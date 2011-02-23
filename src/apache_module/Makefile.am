## this file is input for automake and will generate Makefile.in

AM_CPPFLAGS=-D_REENTRANT $(CXXDEBUGDEFINES) -DDEBUG

CLEANFILES=*~ *.log *.xml ledger.nsmap soapC.cpp soapH.h soapServer.cpp ns.xsd ledger.wsdl soapClient.cpp soapStub.h client


BUILT_SOURCES = soapServer.cpp soapClient.cpp soapC.cpp soapH.h ns.xsd ledger.wsdl ledger.nsmap

lib_LTLIBRARIES = libCalculator.la

noinst_PROGRAMS = client

client_SOURCES=calcclnt.cpp soapClient.cpp soapC.cpp

calculator.cpp: ledger.nsmap soapH.h

libCalculator_la_SOURCES = soapC.cpp \
			soapServer.cpp \
			calculator.cpp 

libCalculator_la_LIBADD = 

client_LDADD=-lgsoap++
libCalculator_la_LDFLAGS = -version-info 0:0:0 -lgsoap++ -module -lananas -lananasplugin

INCLUDES=-I$(top_builddir) -I/usr/include/ananas -I/usr/include/c++/3.3.3  -I$(QTDIR)/include

soapC.cpp soapClient.cpp soapServer.cpp ns.nsmap ns.xsd ledger.wsdl soapH.h ledger.nsmap: calculator.h
	soapcpp2 calculator.h

