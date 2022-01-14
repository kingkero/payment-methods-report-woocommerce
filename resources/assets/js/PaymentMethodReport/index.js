import { Fragment } from '@wordpress/element';
import {__} from '@wordpress/i18n';
import { ReportFilters, TableCard } from '@woocommerce/components';

const PaymentMethodReport = ({ path, query} ) => (
  <Fragment>
    {/*
    <ReportFilters
      query={query}
      path={ path}
      filters={[]}
      advancedFilters={{}}
    />
    */}
    <TableCard
      title={__('Payment Methods', 'payment-methods-report-woocommerce')}
      headers={[
        {
          label: __('Name', 'payment-methods-report-woocommerce'),
          key: 'name',
          required: true,
          isLeftAligned: true,
        },
        {
          label: __('Absolute Usage', 'payment-methods-report-woocommerce'),
          key: 'absolute-usage',
          required: true,
          isNumeric: true,
        },
        {
          label: __('Relative Usage', 'payment-methods-report-woocommerce'),
          key: 'relative-usage',
          required: true,
          isNumeric: true,
        },
        {
          label: __('Money', 'payment-methods-report-woocommerce'),
          key: 'relative-usage',
          required: true,
          isNumeric: true,
        },
      ]}
      rows={[
        [
          {
            display: 'Paypal',
            value: 'Paypal',
          },
          {
            display: 1234,
            value: 1234,
          },
          {
            display: '35 %',
            value: 35,
          },
          {
            display: '581,41 €',
            value: 581.41,
          },
        ],
        [
          {
            display: 'Credit Card',
            value: 'Credit Card',
          },
          {
            display: 2340,
            value: 2340,
          },
          {
            display: '15 %',
            value: 15,
          },
          {
            display: '11,18 €',
            value: 11.18,
          },
        ],
      ]}
      rowsPerPage={10}
      totalRows={2}
    />
  </Fragment>
)

export default PaymentMethodReport
