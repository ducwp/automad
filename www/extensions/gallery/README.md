#Gallery

The Gallery extensions creates a list of preview images which can be viewed in full size by clicking on the thumbnail. The images to be included can be specified by a glob pattern.    
If an image contains an Exif description tag, that tag will be used as the overlay's caption.

---

###Markup

To use the Gallery extension, simply put 

	x(Gallery)
	
somewhere in your markup.

---

###Options

There are several parameters to modify the gallery:

- glob:	`/path/to/images` - The file pattern, relative or absolute path (default: `"*.jpg"`)
- width: `integer` - The thumbnails' width in pixels (default: `200`)
- height: `integer` - The thumbnails' height in pixels (default: `200`)

---

###Example

	x(Gallery {
		glob: "/pages/*/*/*.jpg", 
		width: 250, 
		height: 250
	}) 
	
It is also possible to use a page variable for any of the options:

	x(Gallery {
		glob: p(glob), 
		width: p(width), 
		height: p(height)
	}) 