[<](../index.md) Altapay - PHP Api - Payment request
=================================================

# Request

```php
$request = new \Altapay\Api\Ecommerce\PaymentRequest($auth);
```

### Required

| Method  | Description | Allowed type |
|---|---|---|
| setTerminal(string) | The title of your terminal<br/>It is also possible to pass in a terminal name with the currency as wildcard. Ex. 'My {currency} Terminal' would use the 'My EUR Terminal' if you also pass EUR along as the currency. | string
| setShopOrderId(string) | The id of the order in your webshop. This is what we will post back to you so you know which order a given payment is associated with. | [a-zA-Z0-9]{1,100} |
| setAmount(float) | The amount of the payment in english notation (ex. 89.95)<br />For a subscription the amount is the default amount for each capture.<br />Amount is limited to 2 decimals, an error will be returned if more decimals is supplied. | int, float
| setCurrency(string) | The currency of the payment in ISO-4217 format. Either the 3-digit numeric code, or the 3-letter character code. | string, int - [See currencies](../types/currencies.md)

##### Example

```php
$request = new \Altapay\Api\Ecommerce\PaymentRequest($auth);
$request->setTerminal('my terminal');
$request->setShopOrderId('123456');
$request->setAmount(200.45);
$request->setCurrency('SEK');
```

### Optional

| Method  | Description | Allowed type |
|---|---|---|
| setLanguage(string) | The language of the payment form. Will carry on the optional parameter if specified 1). If the optional parameter is not specified it will hold the language derived from the browsers accept-headers. If none of the languages accepted by the browser are supported the language will default to English "en". | string - [See languages](../types/languages.md)
| setTransactionInfo(array) | This is a one-dimensional associative array. This is where you put any value that you would like to bind to the payment. | array
| setType(string) | The type of the authorization, please refer to the description of [Payment Types](../types/paymenttypes.md)<br />Defaults is "payment". | string - [See payment types](../types/paymenttypes.md)	
| setCcToken(string) | Use the credit_card_token from a previous payment to allow your customer to buy with the same credit card again. The terminal settings have to have "Enable credit card token" enabled for this to work. | [a-z0-9]{41}
| setSaleReconciliationIdentifier(string) | 	If you wish to define the reconciliation identifier used in the reconciliation csv files, you can choose to set it here. This will only be used for paymentAndCapture payments. | string
| setSaleInvoiceNumber(string) | This sets the invoice number to be used on capture, if no invoice number is passed in on capture, and the amount captured is equal to the initial amount. | string
| setSalesTax(float) | 	This sets the sales tax amount that will be used on capture, unless a sales tax is passed on capture api call, and the amount captured is equal to the initial amount. | int, float
| setCookie(string) | Additionally a cookie parameter can be sent to createPaymentRequest, which is then passed back as the complete cookie for the callbacks.<br />For example, if the cookie parameter is set to: "PHPSESSID=asdfasdfdf23; mycookie=mycookievalue", the Cookie header in the callback to your page will be:<br />Cookie: PHPSESSID=asdfasdfdf23; mycookie=mycookievalue | string

### Required on specific payments

| Method  | Description | Allowed type |
|---|---|---|


# Response
