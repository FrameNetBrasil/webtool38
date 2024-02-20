# Webtool 3.8
## Development guidelines

### Structure

* Model
  * Simple interface to database
* Repository
  * Main methods to access data
  * Uses one or more Models
* Controller
  * Actions associated to each route
  * Render views
* View
  * Blade templates for user interface
* Components
  * UI components to build views

### Layout

* index
  * Base template for full rendering pages
  * Includes scripts, css, fonts, menus, etc.
* content
  * A <div> for generic content using javascript
* main
  * Base template for "master" content
* child
  * Template for "master" options
* edit
  * Template for "details" options

### Repository main methods

* list (listing using a filter)
* create (for new records)
* update (for existing records)
* delete

### Services

Services are used to complex operations, using many Repositories



