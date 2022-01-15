/**
 * External dependencies
 */
import { addFilter } from '@wordpress/hooks'
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import PaymentMethodReport from './PaymentMethodReport'

addFilter(
  'woocommerce_admin_reports_list',
  'payment-methods-report-woocommerce',
  (reports) => [
    ...reports,
    {
      report: 'payment-methods-used',
      title: __('Payment Methods', 'payment-methods-report-woocommerce'),
      component: PaymentMethodReport,
    }
  ]
)
