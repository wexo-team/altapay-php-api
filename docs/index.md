Altapay - PHP Api - Index
=========================

Docs: https://testgateway.altapaysecure.com/merchant.php/help/Merchant_API

| Method  | Description |
|---|---|
| [Authentication](authentication.md) | How to authenticate |
| [Card](card.md) | Create a creditcard |
| [Test connection](test_connection.md) | This method requires no authentication and is used for test the connection to our system |
| [Test authentication](test_authentication.md) | This method test your authentication to our system. |
| x [Payments](payments.md) | This is used to find and check the status of a specific payment. This is **NOT** intended for finding multiple payments or creating reports. Please note that the maximum number of transactions returned is 10. |
| x [Capture reservation](capture_reservation.md) | When the funds of a payment has been reserved and the goods are ready for delivery your system should capture the payment. |
| x [Release reservation](release_reservation.md) |  Every now and then you for some reason do not want to capture a payment. In these cases you must cancel it to release the reservation of the funds. |
| x [Refund captured reservation](refund_captured_reservation.md) | Sometimes after delivering the goods/services and capturing the funds you want to repay/refund the customer. Either you want to make a full refund or you only want to make a partial refund. | 
| - [Setup subscription](setup_subscription.md) | This method is used to setup a subscription |
| x [Charge subscription](charge_subscription.md) |  This is used to capture a recurring payments once. You can call this multiple times with the same payment to do several captures. |
| x [Reserve subscription charge](reserve_subscription_charge.md) | This is used to create a preauth from a subscription, as opposed to capturing it right away. You can call this multiple times with the same 'recurring_confirmed' payment to do several preauths. |
| x [Funding list](funding_list.md) | Used to get a list of fundings, which details when and how much money is transfered to your companys bank account. |
| x [Funding download](funding_download.md) | Used to get a comma separated value file containing the details of a funding. |
| x [Custom report](custom_report.md) | Used to get a comma separated value file containing the custom report |
| - Reservation of fixed amount | |
| / [Credit](credit.md) | This will create a Credit payment. The payment can be made with a credit card, or a credit card token and the CVV |
| x [Terminals](terminals.md) | This method will allow you to extract a list of terminals that you have access to |
| x [Invoice text](invoicetext.md) | GetInvoiceText is used for gathering information to print on the customer invoice for invoice/Arvato payments |
| - Create invoice reservation | |
| x [Calculate surcharge](calculate_surcharge.md) | This method is used to calculate the surcharge beforehand, based on a previously completed payment or a terminal, creditcard token, currency combo. |
| x [Query giftcard](query_giftcard.md) | This method is used to get information about a gift card. |

```
x = Docs not written
/ = Some PHP code is missing
- = PHP code missing
```
