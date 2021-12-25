# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
- host the api on a seperate host (configureable api url)
- optional ldap authentication
- brute force detection
- installation tutorial

## [1.0.0] - 2021-06-20
### Added
- api backend [jwt session security, robthree totp, register user, edit user]
- bootstrap frontend [bootstrap fronend, bootstrap themes]

## [1.0.1] - 2021-12-11
### Changed
- implemented j4mie ORM-Mapper
- implementd ORM for user creation
- implemented ORM for user update
- implemented ORM for user login
- implemented ORM for TOTP create, update and delete
### Removed
- remove old database class references
- deleted old database class
- deleted ORM test file

## [1.0.2] 2021-12-13
### Fixed
- Error: jquery-confirm css not found
- Error: mfa secret not properly stored in session
- Error: mfa token validation not submitted on enter

### Added
- set focus to input field in mfa token verification
- implemented groups table in database
- implemented groups field in users table
- implemented admin menu in main menu
- implemented admin permissions check on login and dynamic admin menu display
- created groups manage page

## [1.0.3] 2021-12-14
### Added
- group listing on groups manage page
- implemented pagination for group listing
- created non deletable builtin groups (admins and users)
- created deleteEntryById api call
- made delete button for group entry functional
- created default non deleteable admin account with default password
- set default admin password to: starterAPP.2021
- implemented gruop deletion incl. checking for group membership

## [1.0.4] 2021-12-15
### Added
- api call to add a new group
- implemented add new feature in group management page
- group management feature complete

## [1.0.5] 2021-12-18
### Removed
- Removed "Accounts" menu from admin pages
### Fixed
- Error on signup: wrong group parameter
- Error in MFA reset for other user as logged on admin
### Added
- added users management page
- implemented users pagination
- implemented reset MFA button in user management
- implemented user delete button in user management
- implemented api call for user group change
- implemented api call for user search
- implemented user group change button
- implemented user search field and button in user management
- user management feature complete

## [1.0.5] 2021-12-19
### Added
- Toast notofication on admin user login if mfa is not activated
- accState field in users Table
- added enable / disable user functions in user class
- modified create / update user api calls to reflect column changes
- added active/inactive account status indicator in user management
- added active/inactive MFA indicator in user management
- modified api call for toke verification. Implement check if user is getting disabled in the meantime
- modified login page to notify user if account is disabled
- implemented api call for changing user state
- implemented enable/disable button for user in user management
- added failedLogon column in users table
- added application variable for failed login attempts max count
- added application variable for admin notification on user lock out due to failed logins 

## [1.0.5] 2021-12-20
### Added
- modified logon api to reflect brute force settings
- added mail class for sending mails
- added check for admin notify on disabeling a user due to too many failed logins 
- brute force feature complete
- add loading indicator on user/group management page




