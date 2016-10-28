/*
   +----------------------------------------------------------------------+
   | PHP Version 7                                                        |
   +----------------------------------------------------------------------+
   | Copyright (c) 1997-2016 The PHP Group                                |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.php.net/license/3_01.txt                                  |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
   | Author: David Walker (dave@mudsite.com)                              |
   +----------------------------------------------------------------------+
 */

#include <stdlib.h>
#include <signal.h>
#include <string.h>
#include <ctype.h>
#include <sys/types.h>

#include "php.h"
#include "url.h"

/*!re2c
re2c:yyfill:enable = 0;
re2c:yyfill:check = 0;

re2c:define:YYCTYPE = "unsigned char";
re2c:define:YYCURSOR = "c";
*/

#define get_token(dest, m, c) {                     \
    int len = (c) - (m);                            \
    dest = (char*)emalloc(len + 1);                 \
    memcpy(dest, (m), len);                         \
    dest[len] = '\0';                               \
}

/*
*/

PHPAPI php_url *php_rfc_url_parse(unsigned char *str, size_t slen, zend_long rfcv)
{
	php_url *ret = (php_url*)ecalloc(1, sizeof(php_url));
    unsigned char *m, *c, *YYMARKER;
    c = str;

    /*!re2c
      // General Data
      EOF = "\x00";
      ALPHA = [a-zA-Z];
      DIGIT = [0-9];
      HEXDIG = [0-9a-fA-F];
      SUB_DELIMS = [!$&'()*+,;=];
      GEN_DELIMS = [:/?#\[\]@];
      RESERVED = GEN_DELIMS | SUB_DELIMS;
      UNRESERVED  = ALPHA | DIGIT | [-._~];
      PCT_ENCODED = "%" HEXDIG HEXDIG;
      PCHAR = UNRESERVED | PCT_ENCODED | SUB_DELIMS | ":" | "@";
      UCSCHAR = [\xA0-\uD7FF]           | [\uF900-\uFDCF]         | [\uFDF0-\uFFEF] |
                [\U00010000-\U0001FFFD] | [\U00020000-\U0002FFFD] | [\U00030000-\U0003FFFD] |
                [\U00040000-\U0004FFFD] | [\U00050000-\U0005FFFD] | [\U00060000-\U0006FFFD] |
                [\U00070000-\U0007FFFD] | [\U00080000-\U0008FFFD] | [\U00090000-\U0009FFFD] |
                [\U000A0000-\U000AFFFD] | [\U000B0000-\U000BFFFD] | [\U000C0000-\U000CFFFD] |
                [\U000D0000-\U000DFFFD] | [\U000E1000-\U000EFFFD];
      IUNRESERVED = ALPHA | DIGIT | UCSCHAR | [-._~];
      IPCHAR = IUNRESERVED | PCT_ENCODED | SUB_DELIMS | ":" | "@";
      IPRIVATE = [\uE000-\uF8FF] | [\uF0000-\uFFFFD] | [\u100000-\u10FFFD];


      // Scheme Data
      SCHEME = ALPHA (ALPHA | DIGIT | [+-.])*;


      // Hier-Part
        // Authority Data
        USERINFO  = (UNRESERVED | PCT_ENCODED | SUB_DELIMS | ":")*;
        IUSERINFO = (IUNRESERVED | PCT_ENCODED | SUB_DELIMS | ":")*;

        // Host 
        DEC_OCTET = DIGIT | [1-9] DIGIT | "1" DIGIT{2} | "2" [0-4] DIGIT | "25" [0-5];
        IPV4ADDR = (DEC_OCTET "."){3} DEC_OCTET;
        H16 = HEXDIG{1,4};
        LS32 = (H16 ":" H16) | IPV4ADDR;
        IPV6ADDR = (
            (H16 ":"){7,7} H16|
            (H16 ":" ){1,7} ":" |
            (H16 ":" ){1,6} ":" H16|
            (H16 ":" ){1,5}( ":" H16){1,2}|
            (H16 ":" ){1,4}( ":" H16){1,3}|
            (H16 ":" ){1,3}( ":" H16){1,4}|
            (H16 ":" ){1,2}( ":" H16){1,5}|
            H16 ":" (( ":" H16){1,6})|
            ":" (( ":" H16){1,7}| ":" )|
            "fe80:" ( ":" H16){0,4} "%" [0-9a-zA-Z]{1,}|
            "::" ( "ffff" ( ":0" {1,4}){0,1} ":" ){0,1}IPV4ADDR|
            (H16 ":"){1,4} ":" IPV4ADDR
        );
        IPVFUTURE = "v" HEXDIG+ "." (UNRESERVED | SUB_DELIMS | ":")+;
        IPLITERAL = "[" (IPV6ADDR | IPVFUTURE) "]";
        REG_NAME  = (UNRESERVED | PCT_ENCODED | SUB_DELIMS)+;
        IREG_NAME = (IUNRESERVED | PCT_ENCODED | SUB_DELIMS)+;
        HOST  = IPLITERAL | IPV4ADDR | REG_NAME;
        IHOST = IPLITERAL | IPV4ADDR | IREG_NAME;


        // Path Data
        SEGMENT_NZ_NC = (UNRESERVED | PCT_ENCODED | SUB_DELIMS | "@")+;
        SEGMENT_NZ = PCHAR+;
        SEGMENT = PCHAR*;
        PATH_ABEMPTY = ("/" SEGMENT)*;
        PATH_ABSOLUTE = "/" (SEGMENT_NZ ("/" SEGMENT)*)?;
        PATH_NOSCHEME = SEGMENT_NZ_NC ("/" SEGMENT)*;
        PATH_ROOTLESS = SEGMENT_NZ ("/" SEGMENT)*;
        PATH_EMPTY = "";
        PATH = PATH_ABEMPTY | PATH_ABSOLUTE | PATH_NOSCHEME | PATH_ROOTLESS | PATH_EMPTY;

        ISEGMENT_NZ_NC = (IUNRESERVED | PCT_ENCODED | SUB_DELIMS | "@")+;
        ISEGMENT_NZ = IPCHAR+;
        ISEGMENT = IPCHAR*;
        IPATH_ABEMPTY = ("/" ISEGMENT)*;
        IPATH_ABSOLUTE = "/" (ISEGMENT_NZ ("/" ISEGMENT)*)?;
        IPATH_NOSCHEME = ISEGMENT_NZ_NC ("/" ISEGMENT)*;
        IPATH_ROOTLESS = ISEGMENT_NZ ("/" ISEGMENT)*;
        IPATH_EMPTY = "";
        IPATH = IPATH_ABEMPTY | IPATH_ABSOLUTE | IPATH_NOSCHEME | IPATH_ROOTLESS | IPATH_EMPTY;


    // Query Data
    QUERY = (PCHAR | "/" | "?")*;
    FRAGMENT = (PCHAR | "/" | "?")*;


    // Fragment Data
    IQUERY = (IPCHAR | IPRIVATE | "/" | "?")*;
    IFRAGMENT = (IPCHAR | "/" | "?")*;
    */

    m = c;
    /*!re2c
      EOF { goto end; }
      "" { c = m; goto hier_part; }
      SCHEME ":" {
        get_token(ret->scheme, m, c - 1);
        goto hier_part;
      }
    */

hier_part:
    m = c;
    /*!re2c
      EOF { goto end; }
      "" { c = m; goto path; }
      "//" {
        goto userinfo;
      }
    */

userinfo:
    m = c;
    if (rfcv == PHP_URL_PARSE_RFC3986) {
        /*!re2c
          EOF { goto end; }
          "" { c = m; goto host; }
          USERINFO "@" {
            get_token(ret->user, m, c - 1);
            goto host;
          }
        */
    } else {
        /*!re2c
          EOF { goto end; }
          "" { c = m; goto host; }
          IUSERINFO "@" {
            get_token(ret->user, m, c - 1);
            goto host;
          }
        */
    }

host:
    m = c;
    if (rfcv == PHP_URL_PARSE_RFC3986) {
        /*!re2c
          EOF { goto end; }
          "" { c = m; goto path; }
          HOST {
            get_token(ret->host, m, c);
            goto port;
          }
        */
    } else {
        /*!re2c
          EOF { goto end; }
          "" { c = m; goto path; }
          IHOST {
            get_token(ret->host, m, c);
            goto port;
          }
        */
    }

port:
    m = c;
    /*!re2c
      EOF { goto end; }
      "" { c = m; goto path; }
      ":" DIGIT* {
        char *port;
        get_token(port, m + 1, c);
        ret->port = atoi(port);
        efree(port);
        goto path;
      }
    */

path:
    m = c;
    if (rfcv == PHP_URL_PARSE_RFC3986) {
        /*!re2c
          EOF { goto end; }
          "" { c = m; goto query; }
          PATH {
            get_token(ret->path, m, c);
            goto query;
          }
        */
    } else {
        /*!re2c
          EOF { goto end; }
          "" { c = m; goto query; }
          IPATH {
            get_token(ret->path, m, c);
            goto query;
          }
        */
    }

query:
    m = c;
    if (rfcv == PHP_URL_PARSE_RFC3986) {
        /*!re2c
          EOF { goto end; }
          "" { c = m; goto fragment; }
          "?" QUERY {
            get_token(ret->query, m + 1, c);
            goto fragment;
          }
        */
    } else {
        /*!re2c
          EOF { goto end; }
          "" { c = m; goto fragment;}
          "?" IQUERY {
            get_token(ret->query, m + 1, c);
            goto fragment;
          }
        */
    }

fragment:
    m = c;
    if (rfcv == PHP_URL_PARSE_RFC3986) {
        /*!re2c
          EOF { goto end; }
          "" { c = m; return ret; }
          "#" QUERY {
            get_token(ret->fragment, m + 1, c);
            goto end;
          }
        */
    } else {
        /*!re2c
          EOF { goto end; }
          "" { c = m; return ret; }
          "#" IFRAGMENT {
            get_token(ret->fragment, m + 1, c);
            goto end;
          }
        */
    }

end:
    return ret;
}
