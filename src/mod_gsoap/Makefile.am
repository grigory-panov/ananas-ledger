
AM_CPPFLAGS= $(CXXDEBUGDEFINES)

INCLUDES=-I$(top_builddir) -Ibits

noinst_SCRIPTS=mod_gsoap.so

.c.so:
	apxs2 $(APXS_CXX_OPTIONS) -lgsoap++ $(INCLUDES) -c $< 

mod_gsoap.so : mod_gsoap.c

CLEANFILES=*~ *.o *.so


