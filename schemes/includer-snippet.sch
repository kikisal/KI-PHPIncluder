<script>
((arr) => {
    const $$MODULE_NAME$$ = (window.$$MODULE_NAME$$ || { 
        require: function(e) { throw new TypeError('Not implemented.') } 
    });

    for ( const el of arr )
        $$MODULE_NAME$$.require(el);
})($$INCLUDE_SCRIPTS$$);
</script>