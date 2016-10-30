# NeoFrag CMS Change Log

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
