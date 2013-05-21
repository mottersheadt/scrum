
//
// Help Text
//
// Example of basic usage:
//     
//     logger = new logging();
//     logger.debug( "message" [[, "arg1"], "arg2"]... )
//
// Example using message formatting:
//     
//     logger = new logging();
//     logger.debug( ["this is a message format string: %s", value],
//                   [[, arg1], arg2]... )
//

function logging() {
    
}

logging.prototype = {
    // The function variable 'arguments' is not an array, this will make it into
    // an array:
    //     [].slice.call( arguments )
    
    "log":	function( level, message ) {
        if( this[level] ) {
            if( $.type(message) == "array" )
                message		= sprintf.apply( this, message );

            var args		= [].slice.call( arguments )
            , log		= [ level, message ].concat( args.slice( 2 ) );

            console.log( log );
            return log;
        }
        else {
            console.log( level + " - is not a known logging level." );
        }
    },

    "debug":	function( message ) {
        var level		= "debug"
        , args			= [].slice.call( arguments )
        , args			= [ level, message ].concat( args.slice( 1 ) );

        return this.log.apply( this, args );
    },
    "info":	function( message ) {
        var level		= "info"
        , args			= [].slice.call( arguments )
        , args			= [ level, message ].concat( args.slice( 1 ) );

        return this.log.apply( this, args );
    },
    // Same as warning
    "warn":	function( message ) {
        var level		= "warning"
        , args			= [].slice.call( arguments )
        , args			= [ level, message ].concat( args.slice( 1 ) );

        return this.log.apply( this, args );
    },
    // Same as warn
    "warning":	function( message ) {
        var level		= "warning"
        , args			= [].slice.call( arguments )
        , args			= [ level, message ].concat( args.slice( 1 ) );

        return this.log.apply( this, args );
    },
    "error":	function( message ) {
        var level		= "error"
        , args			= [].slice.call( arguments )
        , args			= [ level, message ].concat( args.slice( 1 ) );

        return this.log.apply( this, args );
    },
    "critical":	function( message ) {
        var level		= "fatal error"
        , args			= [].slice.call( arguments )
        , args			= [ level, message ].concat( args.slice( 1 ) );

        return this.log.apply( this, args );
    }

}

/**
   sprintf() for JavaScript 0.6

   Copyright (c) Alexandru Marasteanu <alexaholic [at) gmail (dot] com>

   Redistribution and use in source and binary forms, with or without
   modification, are permitted provided that the following conditions are met:
       * Redistributions of source code must retain the above copyright
         notice, this list of conditions and the following disclaimer.
       * Redistributions in binary form must reproduce the above copyright
         notice, this list of conditions and the following disclaimer in the
         documentation and/or other materials provided with the distribution.
       * Neither the name of sprintf() for JavaScript nor the
         names of its contributors may be used to endorse or promote products
         derived from this software without specific prior written permission.

   THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
   ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
   WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
   DISCLAIMED. IN NO EVENT SHALL Alexandru Marasteanu BE LIABLE FOR ANY
   DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
   (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
   LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
   ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
   (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
   SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

   All rights reserved.
**/

function str_repeat(i, m) {
	for (var o = []; m > 0; o[--m] = i);
	return o.join('');
}

function sprintf() {
	var i = 0, a, f = arguments[i++], o = [], m, p, c, x, s = '';
	while (f) {
		if (m = /^[^\x25]+/.exec(f)) {
			o.push(m[0]);
		}
		else if (m = /^\x25{2}/.exec(f)) {
			o.push('%');
		}
		else if (m = /^\x25(?:(\d+)\$)?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-fosuxX])/.exec(f)) {
			if (((a = arguments[m[1] || i++]) == null) || (a == undefined)) {
				throw('Too few arguments.');
			}
			if (/[^s]/.test(m[7]) && (typeof(a) != 'number')) {
				throw('Expecting number but found ' + typeof(a));
			}
			switch (m[7]) {
				case 'b': a = a.toString(2); break;
				case 'c': a = String.fromCharCode(a); break;
				case 'd': a = parseInt(a); break;
				case 'e': a = m[6] ? a.toExponential(m[6]) : a.toExponential(); break;
				case 'f': a = m[6] ? parseFloat(a).toFixed(m[6]) : parseFloat(a); break;
				case 'o': a = a.toString(8); break;
				case 's': a = ((a = String(a)) && m[6] ? a.substring(0, m[6]) : a); break;
				case 'u': a = Math.abs(a); break;
				case 'x': a = a.toString(16); break;
				case 'X': a = a.toString(16).toUpperCase(); break;
			}
			a = (/[def]/.test(m[7]) && m[2] && a >= 0 ? '+'+ a : a);
			c = m[3] ? m[3] == '0' ? '0' : m[3].charAt(1) : ' ';
			x = m[5] - String(a).length - s.length;
			p = m[5] ? str_repeat(c, x) : '';
			o.push(s + (m[4] ? a + p : p + a));
		}
		else {
			throw('Huh ?!');
		}
		f = f.substring(m[0].length);
	}
	return o.join('');
}

//
// Unit testing for the logging framework
//
function test_logging() {
    function test( result, length, level, message ) {
        console.log( result )
        if( result.length != length
            || result[0] != level
            || result[1] != message
          ) {
            return false;
        }

        return true;
    }
    var logger			= new logging()
    , tests			= [];

    tests.push({
        "expect":	true,
        "test":		logger.debug( "This is a debug message" ),
        "length":	2,
        "level":	"debug",
        "message":	"This is a debug message"
    });
    tests.push( {
        "expect":	true,
        "test":		logger.error( ["This is a %s message", "error"] ),
        "length":	2,
        "level":	"error",
        "message":	"This is a error message"
    });

    for( i=0; i<tests.length; i++ ) {
        var t			= tests[i]
        , failmsg		= "logging has broken";
        
        t.expect == false
            ? assert( test( t.test, t.length, t.level, t.message ),	failmsg )
            : assert( ! test( t.test, t.length, t.level, t.message ),	failmsg );
    }
}
