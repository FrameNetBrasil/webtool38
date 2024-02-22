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
* Data
    * DTO (Data Transfer Objects)

### Layout

* index
  * Base template for full rendering pages
  * Includes scripts, css, fonts, menus, etc.
* content
  * A <div> for generic content using javascript
  * It is used when sending HTML fragments (not the whole page)
* main
  * Base template for "master" content
* edit
  * Template for "details" options

### Common views

* main
  * Base view for CRUD operations
* browse
  * Base view for listing (search form and div for grid)
* grid
  * View for listing records (associated to browse view)
* edit
  * Base view for editing element using options (menu)
* new
  * View for creating a new element (using a form)
* child
  * View for child elements, including a for new element and a grid for existing elements
* grid (for child elements)
  * view for listing detail elements associated to a master element (included by child view)
* formNew
  * View for create a detail element associated a master element  (included by child view)
* formEdit
  * View for edit a detail element associated a master element

### Repository main methods

* list (listing using a filter)
* listForSelect (listing for a combobox)
* create (for new records)
* update (for existing records)
* delete

### Services

Services are used to complex operations, using many Repositories



