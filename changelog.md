# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
- host the api on a seperate host (configureable api url)
- goups and admin role
- admin interface for manging user accounts
- admin interface for managing groups
- optional ldap authentication
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


