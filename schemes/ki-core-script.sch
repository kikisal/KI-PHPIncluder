<script src="$$KI_BASEDIR_ASSETS$$/ki-core.js"></script>
<script>
    window.ki.Require = new ki.__Require({
        resHandler: new ki.DefaultResourceHandler(),
        ep_base: "$$KI_BASEDIR_ASSETS$$"
    });
    window.ki.require = function(m) {
        ki.Require.include(m);
    }
</script>