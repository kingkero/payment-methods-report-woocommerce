/**
 * External dependencies
 */
import {__} from '@wordpress/i18n';
import { TableCard } from '@woocommerce/components';

const PaymentMethodTable = ({ data }) => (
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
    rows={data}
    rowsPerPage={10}
    totalRows={data.length}
  />
)

export default PaymentMethodTable
