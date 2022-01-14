/**
 * External dependencies
 */
import { Fragment, useState, useEffect } from '@wordpress/element';
import { Spinner } from '@wordpress/components'
import {__} from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch'
import { ReportFilters } from '@woocommerce/components';

/**
 * Internal dependencies
 */
import PaymentMethodTable from './PaymentMethodTable'

const PaymentMethodReport = ({ path, query} ) => {
  const [data, setData] = useState(null)

  useEffect(() => {
    apiFetch({
      path:'kk/pmr/v1/report',
    }).then(fetchedData => {
      setData(fetchedData)
    })
  }, [query])

  return (
    <Fragment>
      {/*
      <ReportFilters
        query={query}
        path={ path}
        filters={[]}
        advancedFilters={{}}
      />
      */}
      {!Array.isArray(data) ? <Spinner /> : <PaymentMethodTable data={data} />}
    </Fragment>
  )
}

export default PaymentMethodReport
