((out) => {

    const Importer = function() {
        this.name = undefined;
        this.import = function(node, res) {
            throw new Error('Not defined');
        };
    };

    function CSSImporter() {
        this.name = 'css';

        this.import = function(node, res) {
            const el = document.createElement('link');
            el.type = 'text/css';
            el.rel = 'stylesheet';
            el.href = res;
            node.appendChild(el);
        }
    }

    CSSImporter.prototype = Object.create(Importer.prototype);    

    function JSImporter() {
        this.name = 'js';

        this.import = function(node, res) {
            const el = document.createElement('script');
            el.type = 'text/javascript';
            el.src = res;
            node.appendChild(el);
        }
    }

    JSImporter.prototype = Object.create(Importer.prototype);


    function ResourceHandler() {
        this.importers = [];
        this.node = undefined;
    }

    ResourceHandler.prototype = {
        getImporter: function(key) {
            for ( const importer of this.importers ) {
                if ( importer.name === key )
                    return importer;
            }
            
            return null;
        },
        
        handle: function(type, res) {
            const imp = this.getImporter(type);
            if ( !imp )
                throw new Error(`Importer for ${type} not found`)
            
            imp.import(this.node, res);
        },

        hasImporter: function(type) {
            for ( const importer of this.importers ) {
                if ( importer.name === type )
                    return true;
            }
            
            return false;
        }
    }

    function DefaultResourceHandler() {
        ResourceHandler.apply(this);

        this.importers = [
            new CSSImporter(),
            new JSImporter()
        ];

        this.node = document.querySelector('head');
    }

    DefaultResourceHandler.prototype = Object.create(ResourceHandler.prototype);
    DefaultResourceHandler.prototype.constructor = ResourceHandler;
    


    const Utils = {
        getExtension: function(fn) {
            const els = fn.split('.');
            return els[els.length - 1] || null;
        },

        slashClamp: function(str) {
            return !str ? null : (str[str.length - 1] === '/' ? str : str.concat('/'));
        }
    };

    function __Require( config ) {
        this.res_handler = config.resHandler;
        this.ep_base = Utils.slashClamp(config.ep_base);
    }

    __Require.prototype = {
        include: function(assets) {
            const type = Utils.getExtension( assets );
            if ( !type )
                throw new Error(`Invalid file extension: ${assets}`);

            if ( !this.res_handler.hasImporter(type) )
                throw new Error(`Extension '${type}' not supported`);

            const res =  this.ep_base.concat(assets);

            this.res_handler.handle(type, res);
        }
    }

    out.ki = {
        __Require: __Require,
        DefaultResourceHandler: DefaultResourceHandler
    };

})(window);