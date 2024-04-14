const defaultConfig = require('@wordpress/scripts/config/webpack.config')

// from https://www.npmjs.com/package/@woocommerce/dependency-extraction-webpack-plugin?activeTab=code
//
const WPDependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );

const packages = [
  // wc-admin packages
  '@woocommerce/admin-layout',
  '@woocommerce/block-templates',
  '@woocommerce/components',
  '@woocommerce/csv-export',
  '@woocommerce/currency',
  '@woocommerce/customer-effort-score',
  '@woocommerce/data',
  '@woocommerce/date',
  '@woocommerce/dependency-extraction-webpack-plugin',
  '@woocommerce/eslint-plugin',
  '@woocommerce/experimental',
  '@woocommerce/explat',
  '@woocommerce/extend-cart-checkout-block',
  '@woocommerce/navigation',
  '@woocommerce/notices',
  '@woocommerce/number',
  '@woocommerce/product-editor',
  '@woocommerce/tracks',
  // wc-blocks packages
  '@woocommerce/blocks-checkout',
  '@woocommerce/blocks-components',
  '@woocommerce/block-data',
  '@woocommerce/blocks-registry',
  '@woocommerce/settings',
]


const WOOCOMMERCE_NAMESPACE = '@woocommerce/';

/**
 * Given a string, returns a new string with dash separators converted to
 * camelCase equivalent. This is not as aggressive as `_.camelCase` in
 * converting to uppercase, where Lodash will also capitalize letters
 * following numbers.
 *
 * @param {string} string Input dash-delimited string.
 *
 * @return {string} Camel-cased string.
 */
function camelCaseDash( string ) {
  return string.replace( /-([a-z])/g, ( _, letter ) => letter.toUpperCase() );
}

const wooRequestToExternal = ( request, excludedExternals ) => {
  if ( packages.includes( request ) ) {
    const handle = request.substring( WOOCOMMERCE_NAMESPACE.length );
    const irregularExternalMap = {
      'block-data': [ 'wc', 'wcBlocksData' ],
      'blocks-registry': [ 'wc', 'wcBlocksRegistry' ],
      settings: [ 'wc', 'wcSettings' ],
    };

    if ( ( excludedExternals || [] ).includes( request ) ) {
      return;
    }

    if ( irregularExternalMap[ handle ] ) {
      return irregularExternalMap[ handle ];
    }

    return [ 'wc', camelCaseDash( handle ) ];
  }
};

const wooRequestToHandle = ( request ) => {
  if ( packages.includes( request ) ) {
    const handle = request.substring( WOOCOMMERCE_NAMESPACE.length );
    const irregularHandleMap = {
      data: 'wc-store-data',
      'block-data': 'wc-blocks-data-store',
      'csv-export': 'wc-csv',
    };

    if ( irregularHandleMap[ handle ] ) {
      return irregularHandleMap[ handle ];
    }

    return 'wc-' + handle;
  }
};

class WooCommerceDependencyExtractionWebpackPlugin extends WPDependencyExtractionWebpackPlugin {
  externalizeWpDeps( _context, request, callback ) {
    let externalRequest;

    // Handle via options.requestToExternal first
    if ( typeof this.options.requestToExternal === 'function' ) {
      externalRequest = this.options.requestToExternal( request );
    }

    // Cascade to default if unhandled and enabled
    if (
      typeof externalRequest === 'undefined' &&
      this.options.useDefaults
    ) {
      externalRequest = wooRequestToExternal(
        request,
        this.options.bundledPackages || []
      );
    }

    if ( externalRequest ) {
      this.externalizedDeps.add( request );

      return callback( null, externalRequest );
    }

    // Fall back to the WP method
    return super.externalizeWpDeps( _context, request, callback );
  }

  mapRequestToDependency( request ) {
    // Handle via options.requestToHandle first
    if ( typeof this.options.requestToHandle === 'function' ) {
      const scriptDependency = this.options.requestToHandle( request );
      if ( scriptDependency ) {
        return scriptDependency;
      }
    }

    // Cascade to default if enabled
    if ( this.options.useDefaults ) {
      const scriptDependency = wooRequestToHandle( request );
      if ( scriptDependency ) {
        return scriptDependency;
      }
    }

    // Fall back to the WP method
    return super.mapRequestToDependency( request );
  }
}

module.exports = {
	...defaultConfig,
	plugins: [
		...defaultConfig.plugins.filter(
			(plugin) =>
				plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
		),
		new WooCommerceDependencyExtractionWebpackPlugin({}),
	],
  output: {
    hashFunction: 'xxhash64',
  },
}
