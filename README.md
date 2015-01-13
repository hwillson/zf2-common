# zf2-common

Common [Zend Framework 2](http://framework.zend.com) helper/utility classes.

## Classes

### Authentication

- **Zf2Common\Authentication\Adapter\BcryptDbTable**

  A Bcrypt DbTable authentication adapter, used to bcrypt entered passwords, comparing with stored bcrypted passwords.

- **Zf2Common\Authentication\Location**

  Get a requests public facing originating IP.

### Database

- **Zf2Common\Db\Adapter\ReadOnlyReadyAdapter**

  A custom database adapter that prevents inserts/updates from running if an application has been put into read-only mode.

-  **Zf2Common\Db\TableGateway\ReadOnlyReadyTableGateway**

  A custom table gateway that prevents inserts/updates from running if an application has been put into read-only mode.

- **Zf2Common\Db\AbstractTable**

  Re-usable parent table class.

### I18N

-  **Zf2Common\I18n\Translator\Loader\DatabaseTranslationLoader**

  Load translations from a database.

- **Zf2Common\I18n\Language**

  Common language helper.

### Mail

- **Zf2Common\Mail\Message**

  Extends ZF2's default MailMessage class to add custom headers (like X-SMTPAPI).

### Model

- **Zf2Common\Model\AbstractBase**

  Re-usable base model.

### MVC

-  **Zf2Common\Mvc\Controller\Plugin\LanguageSelectorPlugin**

  Extracts a selected language from a request, then sets the appropriate locale and translate object.

### Access Control

- **Zf2Common\Permissions\Acl\Acl**

  Access control list implementation handling roles and resource access.

### Session Handling

- **Zf2Common\Session\UserSession**

  User session model representing ZF2's default database backed session approach.

- **Zf2Common\Session\UserSessionMapper**

  Maps user session model objects to the database, following ZF2's default database backed session approach.

### View Helpers

- **Zf2Common\View\Helper\AbstractBaseHelper**

  Re-usable parent view helper.

- **Zf2Common\View\Helper\Config**

  Expose a loaded config array in a view.

- **Zf2Common\View\Helper\GetUrl**

  Get the current request URL, replacing keys/values as needed in the request string.

- **Zf2Common\View\Helper\IsReadOnly**

  Checks to see if an application config entry exists stating that the application is in ready only mode.

- **Zf2Common\View\Helper\Ordinal**

  Return the ordinal representation of a number.

- **Zf2Common\View\Helper\Session**

  Expose the current session object in a view.

- **Zf2Common\View\Helper\TrimUrl**

  Trim a URL for display.

- **Zf2Common\View\Helper\UrlEncodedRequestParams**

  Return an array with all existing request  parameters, URL encoded.
