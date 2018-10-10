# Nova MailChimp Tool

A Laravel Nova tool that integrate with MailChimp to:

- Number of subscribers and unsubscribers in a list.
- Add Subscriber to a list
- Remove Subscriber from a list
- Create Campaign (Send Email Message)

## Installation:

You can install the package in to a Laravel app that uses Nova via composer:

```bash
composer require naif/mailchimp-tool
```

## Usage:
Add the below to app/Providers/NovaServiceProvder.php

```php
  public function tools()
  {
      return [
           new MailchimpTool()
      ];
  } 
```

## Pre Requirements
Create an account with [MailChimp](https://mailchimp.com):
- Get your api key
- Create a list and get it's ID
- Create a template with [Variable Content Area](https://mailchimp.com/help/create-editable-content-areas-with-mailchimps-template-language/#Variable_Content_Area) and must name it "eventmessage"

Then add the below to your .env file
```php
NOVA_MAILCHIMP_API_KEY=xxxxxxxxxxxxxxxxx-us19
NOVA_MAILCHIMP_LIST_ID=xxxxxx
NOVA_MAILCHIMP_TEMPLATE_ID=12345
NOVA_MAILCHIMP_FROM_NAME=Name
NOVA_MAILCHIMP_FROM_EMAIL=email@email.com
```
## Screenshots

<img src="https://github.com/naifalshaye/mailchimp/blob/master/screenshots/image1.png" width="900">

<img src="https://raw.githubusercontent.com/naifalshaye/mailchimp/master/screenshots/image2.png" width="900">

There is an issue with the tinymce editor Vue package icons are not showing probably.
<img src="https://raw.githubusercontent.com/naifalshaye/mailchimp/master/screenshots/image3.png" width="700">

To Fix this, copy the (fonts) folder to your Laravel project main directory as the full path as below:

"/public/fonts/vendor/tinymce/skins/lightgray"

<img src="https://raw.githubusercontent.com/naifalshaye/mailchimp/master/screenshots/image4.png" width="700">

## Credits
[Drew McLellan](https://github.com/drewm/mailchimp-api)

[Dyonir](https://github.com/dyonir/vue-tinymce-editor)

## Support:
naif@naif.io

https://www.linkedin.com/in/naif

## License:
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
