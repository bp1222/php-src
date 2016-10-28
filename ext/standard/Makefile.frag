$(srcdir)/var_unserializer.c: $(srcdir)/var_unserializer.re
	@(cd $(top_srcdir); $(RE2C) --no-generation-date -b -o ext/standard/var_unserializer.c ext/standard/var_unserializer.re)

$(srcdir)/url_scanner_ex.c: $(srcdir)/url_scanner_ex.re
	@(cd $(top_srcdir); $(RE2C) --no-generation-date -b -o ext/standard/url_scanner_ex.c	ext/standard/url_scanner_ex.re)

$(srcdir)/url_parser_ex.c: $(srcdir)/url_parser_ex.re
	@(cd $(top_srcdir); $(RE2C) --no-generation-date -b -8 -o ext/standard/url_parser_ex.c	ext/standard/url_parser_ex.re)

$(builddir)/info.lo: $(builddir)/../../main/build-defs.h

$(builddir)/basic_functions.lo: $(top_srcdir)/Zend/zend_language_parser.h
