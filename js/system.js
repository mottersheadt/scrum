
//
// Help Text
//
// Example of basic usage:
//     
//     logger = new logging();
//     logger.debug( "message" [[, "arg1"], "arg2"]... )
//

window.system = new function() {

    this.alert		= function( title, message ) {
        alert( title + "\n\n" + message );
        return message;
    }

}

//
// Unit testing for the logging framework
//
