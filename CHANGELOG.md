# NeoFrag CMS Change Log

## [Alpha 0.1.6](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1.6) (2017-03-26)
[Change Log](https://github.com/NeoFragCMS/neofrag-cms/compare/alpha0.1.5.3...alpha0.1.6)

**Classes**

- [Library] Reset method returns $this
- [Library] Updating internal id when libraries are reset
- [Loader] Is a Class now
- [Module] Replace get_output() by __toString() method
- [Module] UTF-8 BOM removing
- [NeoFrag] Adding magic methods static_... and ..._if
- [NeoFrag] Displaying errors when __call and __get methods failed
- [NeoFrag] Removing camelcase for library names
- [Translatable] Is removed

**Core**

- [Access] Authorizations use groups hierarchy (so the ambiguity notion is removed)
- [Addons] Removing addons sort
- [Db] Adding REPLACE instruction
- [Output] Merging template and output core libraries
- [Router] Improving router call and removing useless code
- [Url] Adding new core library

**Helpers**

- [Countries] Adding countries / flags data
- [User_Agent] Update crawlers list

**Libraries**

- [Breadcrumb] Is a Library now
- [Button] Adding new library instead of helper
- [File] Adding method to generate unique filename
- [Html] Adding new library
- [Label] Adding new library
- [Modal] Adding new library
- [MySQLDump] Fix escaping colnames and convert float format
- [Network] Adding new library instead of helper
- [Pagination] Adding $_GET params on links

**Modules**

- [Events] Adding new module
- [Forum] Keeping form data if errors occur when create or edit topic
- [Games] Adding get_modes_list() method
- [Monitoring] If download failed, monitoring is not anymore blocked and display results
- [Recruits] Adding new module
- [Teams] Improvement for events module
- [User] Groups can be hidden
- [User] Groups can be hierarchized

**NeoFrag**

- .htaccess -MultiViews and some comments to help users
- Adding authenticators (Battle.net, Facebook, GitHub, Google, Linkedin, Steam, Twitch, Twitter)
- Adding vcenter css
- Autoload library classes
- Classes Zone, Row, Col, Panel, Panel_Box, Panel_Pagination, Widget_View, Button_Back are now libraries
- Every .html url extension is removed
- Globals $loader and $NeoFrag are removed of views (and $this can be used)
- Improving objects call (Module, Theme, Widget, Controller, Model, helper, view, lang and form)
- Inheriting libraries are grouped in the same folder
- Overall fixing of tiny errors
- Replace __autoload by spl_autoload_register
- Replace NeoFrag::loader() by NeoFrag()

**Themes**

- [Admin] Modules are now categorized (default and gaming)
- [Default] Improving geolocalisation and user-agent API calls

**Plugins updates**

- Font Awesome v4.7
- PHPMailer v5.2.22

## [Alpha 0.1.5.3](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1.5.3) (2016-12-17)
[Change Log](https://github.com/NeoFragCMS/neofrag-cms/compare/alpha0.1.5.2...alpha0.1.5.3)

**Core**

- [Config] Improving url reading
- [Db] Adding 503 Service Unavailable HTTP header when database is unavailable
- [Router] Fix error when controller result is NULL

**NeoFrag**

- Overall fixing of tiny php errors (warnings, notices, deprecated...)
- Removing some useless codes

**LiveEditor**

- Custom zones are now available for pages

**Libraries**

- [MySQLDump] Fix some errors
- [Tab] Using a real callback
- [Table] Fix ajax output for search and sort

**Modules**

- [Forum] Fix unread status for subforums
- [News] Fix categories deletion
- [Search] Request is now transmitted in $_GET['q']
- [Statistics] Fix bug when all categories are unchecked
- [User] Fix error on group form

**Helper**

- [File] Removing useless 2nd argument
- [Network] Function network_get returns FALSE if HTTP response code is not 200
- [String] Add default encoding ASCII

## [Alpha 0.1.5.2](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1.5.2) (2016-11-26)
[Change Log](https://github.com/NeoFragCMS/neofrag-cms/compare/alpha0.1.5.1...alpha0.1.5.2)

**NeoFrag**

- Overall fixing of tiny errors (warnings, notices, deprecated...)

**Classes**

- [Controller] Adding method has_method to check if method exists
- [Library] It's not necessary to instantiate new object after reset

**Core**

- [Db] Adding lock and unlock methods
- [Debug] Using the default handler to continue to deal with errors (and adding DB errors)

**Database**

- Increase user_agent column to 250 chars

**Helpers**

- [Dir] Adding a second callback for dirs on dir_scan method

**Libraries**

- [Form] Using a method to get forms values
- [Form][Fixes #10] Adding random token on forms

**Modules**

- [Monitoring] Prevent the deactivation

**Security**

- [Fixes #18]XSS vulnerabilities
- [Fixes #19]Using Secure and HttpOnly flags to set session cookie

## [Alpha 0.1.5.1](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1.5.1) (2016-11-06)
[Change Log](https://github.com/NeoFragCMS/neofrag-cms/compare/alpha0.1.5...alpha0.1.5.1)

**Modules**

- [Addons] Fix reset theme feature
- [Forum] Removing unused icons
- [Monitoring] Adding some advices (PHP 7 + MySQLi driver)
- [Monitoring] Auto cleaning neofrag/ folder
- [Monitoring] Fix disk usage value
- [Monitoring] Fix download problems
- [Monitoring] Fix files replacement (oops php line commented out 😱)
- [Monitoring] Fix infinite loop when sorting files tree
- [User] Fix update member

**Themes**

- [Admin] Fix notices
- [Admin] Hide features area when is empty

## [Alpha 0.1.5](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1.5) (2016-10-30)
[Change Log](https://github.com/NeoFragCMS/neofrag-cms/compare/alpha0.1.4.2...alpha0.1.5)

**Features**

- Auto updating and monitoring module
- Limited access to admin panel

**Core**

- [Access] Fix ambiguity problem when two groups were equals
- [Config] Fix empty HTTP_ACCEPT_LANGUAGE
- [Db] Adding get_info, get_size, execute, results, free and escape_string methods
- [Db] Using MySQLi driver when enabled
- [Loader] Fix overrides loading priority
- [Router] Checker methods throw unfound exception when return NULL

**Database**

- Removing sessions unique key
- MySQL 5.7 compatibility SQL-MODE STRICT_ALL_TABLES
- [MySQLi] Fix mysqli_stmt::get_result() error
- [MySQLi] Synchronizing timezone between PHP and MySQL
- [MySQL] Synchronizing timezone between PHP and MySQL

**Helper**

- [Assets] Adding Content-Length HTTP header
- [Input] Add optionnal arg to post_check function
- [Network] Add new helper
- [String] Add version_format function (transform Alpha 0.1.4.2 to 0.1.4.2)
- [Dir] Add new helper

**Modules**

- [Statistics] Add new module
- [User] Migration admin panel from member module

**Minor changes**

- Assets are transmitted directly by HTTP server
- Convert array syntax to []
- CSS cache is forced to reload after theme update
- Dynamic loading of libraries
- Extension method moved in NeoFrag class and json data are formatted automatically
- Removing xml extension requirements (utf8_encode / utf8_decode)

## [Alpha 0.1.4.2](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1.4.2) (2016-07-30)
[Change Log](https://github.com/NeoFragCMS/neofrag-cms/compare/alpha0.1.4.1...alpha0.1.4.2)

**Core**

- [Db] Adding query method
- [Groups] Use colors from color helper

**Helper**

- [Notify] Fix color classes

**Library**

- [Form] Add legend type

**LiveEditor**

- Fix widget settings loading
- Fix widget title updating

**Module**

- [Addons] Fix theme reset function
- [Addons] Minor adjustments
- [Forum] Fix forum visibility on search
- [Settings] Global improving of settings panel

**Widget**

- [Partners] Fix css class

## [Alpha 0.1.4.1](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1.4.1) (2016-05-26)
[Change Log](https://github.com/NeoFragCMS/neofrag-cms/compare/alpha0.1.4...alpha0.1.4.1)

**Features**

- Notifications

**Core**

- [Config] Add reset method to reload settings after deletions
- [User] Avatar function standardization

**Helpers**

- [Array] Add array_natsort function
- [Input] Add post_check function

**Libraries**

- [BBCode] Fix Youtube HTTPS and add img-responsive

**LiveEditor**

- Error widget removed from selector
- Fix input height
- Fix widget adding and editing

**Modules**

- [Addons] Fix install new addon
- [Gallery] Fix ajax picture ulpoad
- [User] Fix forum access on recent activities

**Widgets**

- [Partners] Fix css class
- [User] Fix typo

## [Alpha 0.1.4](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1.4) (2016-05-07)
[Change Log](https://github.com/NeoFragCMS/neofrag-cms/compare/alpha0.1.3...alpha0.1.4)

**Features**

- Addons management
- PHP 7 compatibility with MySQLi
- Debugbar

**Core**

- [Col] Adding array support in Col constructor
- [Breadcrumb] Add breadcrumb on pages module
- [Breadcrumb] First arg is now optional (using module_title and module_icon)
- [i18n] Minor adjustments

**Libraries**

- [Captcha] Using curl
- [Email] Improving with PHPMailer lib
- [Form] Fix value in date time inputs
- [Form] Improving forms (number, phone, size and placeholder)

**LiveEditor**

- Minor adjustments

**Modules**

- [Awards] Add module
- [Forum] Fix member type when edit message
- [Forum] Fix subforums access error
- [Forum] Improving user profile display
- [Gallery] Fix picture preview and back link
- [Games] Adding maps and modes
- [Partners] Add module
- [Search] Add new module
- [Settings] Ability to choose a custom page as home page
- [Settings] Remove script tags automatically
- [Talks] Fix deleted user avatar
- [Teams] Fix game when updating teams
- [User] Add private messaging, user profile and widgets improved
- [User] Case insensitive login check

**Widgets**

- [Forum] Add subforum messages
- [Forum][Statistics] Fix announce counter
- [Navigation] Settings improving

**Fixes**

- Add https support

**Helpers**

- [Assets] Add file_name param on asset()

**Themes**

- [Default] Minor adjustments

**Security**

- Global improving

## [Alpha 0.1.3](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1.3) (2015-11-29)
[Change Log](https://github.com/NeoFragCMS/neofrag-cms/compare/alpha0.1.2...alpha0.1.3)

**Features**

- Internationalization (french and english)
- Google reCAPTCHA
- Maintenance page
- Moving forum topics

**Core**

- [Access] Setting default access to FALSE
- [Breadcrumb] Add breadcrumb on forum module
- [Output] Fix dispositions request

**Libraries**

- [Email] Fix base url
- [Form] Using bootstrap-datetimepicker (adding datetime and time inputs)

**LiveEditor**

- Global improving

**Modules**

- [Gallery] Fix php error on editing
- [Members] Destroy user sessions when delete one
- [User] Display correctly user last name
- [User] Fix date on sessions historic

**Widgets**

- [Talks] Fix read permission

**Fixes**

- Fix global $NeoFrag not found in destruct scope
- Replace deleted users by a guest mention

## [Alpha 0.1.2](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1.2) (2015-10-04)
[Change Log](https://github.com/NeoFragCMS/neofrag-cms/compare/alpha0.1.1...alpha0.1.2)

**Features**

- Adding ajax popover mini user profile
- Permissions management

**Core**

- [Config][Fixes #3] Remove getallheaders function (was useless)
- [Group] Fix group url
- [Session] Crawlers detection
- [Session] Improving __destruct for always save user_data
- [Template] Adding loader arg for callbacks

**Helpers**

- [Assets] Adding fa-fw class for Font Awesome icons
- [Buttons] Standardization of adding buttons and changing color to btn-primary
- [File] Move is_asset function from Assets
- [Output] Improving output function
- [String] Improving links detection and adding @Username format support
- [String] Improving url_title function
- [Time] Fix time_span function

**Libraries**

- [Form] Fix &nbsp; in text editor
- [Form] Fix some comparison bugs
- [Form][Fixes #4][Fixes #5] Trim and htmlentities all post entries before the validity check
- [Table] Add number of results
- [Table] Adding td option to return td tags in content
- [Table] Global improving

**LiveEditor**

- Fix widget selectors

**Modules**

- [Forum] Responsive improving
- [Page] Remove link when unpublished and add check page path unicity
- [Teams] Adding the sorting of teams and roles

**Themes**

- [Admin] Global improvement
- [Default] Removing container:'body' for tooltips (and forcing to data-container="body" when needed)
- Removing @import url in css to increase downloading and files caching

**Security**

- Remove templating for security and performances
- XSS vulnerabilities

**Plugins updates**

- Font Awesome v4.4

## [Alpha 0.1.1](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1.1) (2015-07-23)
[Change Log](https://github.com/NeoFragCMS/neofrag-cms/compare/alpha0.1...alpha0.1.1)

**Features**

- Themes management and customization

**Classes**

- [Library] Add copy method
- [Widget_View] Fix error empty settings
- [Zone] Display profiler after row containing the module

**Core**

- [Assets] Force download only for .zip files
- [Config] Adding type argument to update settings
- [Config] Fix config in .css .js files
- [Config] Global improving for ajax requests
- [Config] Insert setting in database if not exists
- [Groups] Fix bugs
- [Database] Add HAVING clause
- [Output] Add .module .module-admin .module-... classes to back-office
- [Session] Fix history url bug

**Helpers**

- [Color] New helper
- [File] Add rmdir_all function to remove non empty directories
- [File] Improving image_resize function: keeping the aspect ratio and transparency for .gif pictures

**Libraries**

- [Editor] BBcode update
- [File] Code improvement
- [Form] Color-picker improvement
- [Form] Fix color selector bug
- [Form] Fix file deletion returned value
- [Form] Fix file input required
- [Form] Global improvement of library and update icon-picker plugin

**Modules**

- [Contact] Fix icon envelope
- [Forum] Adding subforums and url forums
- [Forum] Fix bug is_authorized
- [Gallery] New module
- [Members] Add back button
- [Teams] Fix check_team sql
- [Teams] Fix players list
- [User] Add checking of birthday
- [User] Fix delete session bug

**Themes**

- [Admin] Fix toggle button to hide sidebar
- [Admin] Remove div end tag not opened
- [Default] Adding internal customisation
- [Default] Default popover container is body and default trigger is hover

**Widgets**

- [Forum][Statistics] Fix announces counter
- [Gallery] New widgets
- [Header] Adding alignment, colors and titles configuration
- [Members][Online] Fix counting online users bug
- [Navigation] Accept https url and fix delete button bug
- [Teams] New widget

**Plugins updates**

- Bootstrap v3.3.5
- Bootstrap-Iconpicker v1.7.0 (https://github.com/NeoFragCMS/bootstrap-iconpicker)
- Bootstrap-Colorpicker v2.2 (https://github.com/NeoFragCMS/bootstrap-colorpicker)
- WysiBB v1.5.1 (https://github.com/NeoFragCMS/wysibb)

## [Alpha 0.1](https://github.com/NeoFragCMS/neofrag-cms/tree/alpha0.1) (2015-05-31)
