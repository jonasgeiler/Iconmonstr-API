# iconmonstr API

This is an unofficial API for iconmonstr.com!  
Fetch icons, collections and other directly from their website.  
This page crawls all data from iconmonstr.com, no database is used.  
That's why the requests can take some time occasionally.

The API was made in PHP 7 with the limonade.php framework.

## Installation

If you don't want to use my free-hosted API, you can install it by yourself.  
All you need is PHP 7! (other versions not tested - might work)  
There's no configuration or anything needed. Just upload the whole repo onto your server!

## Todo

- Better error messages
- Search for collections
- png support for icons (costumizing and coloring images and getting their download link)

## API

Please note that the api is not fully RESTful!

- Icons
    - [GET /icons/search/](#get-iconssearch)
    - [GET /icons/popular/](#get-iconspopular)
    - [GET /icons/new/](#get-iconsnew)
    - [GET /icons/:slug/](#get-iconsslug)
- Categories
    - [GET /categories/](#get-categories)
    - [GET /categories/:slug/](#get-categoryslug)
- Collections
    - [GET /collections/](#get-collections)
    - [GET /collections/:slug/](#get-collectionsslug)
- Icon Requests
    - [GET /icon-requests/](#get-icon-requests)
    - [GET /icon-requests/:slug/](#get-icon-requestsslug)

### GET /icons/search/

> Get icons by search query

#### Resource URL

`https://iconmonstr-api.2ix.at/icons/search/`

#### Parameters

Parameter                  | Description
---------                  | -----------
**query**<br>*required* | Search query.<br>**Example Values**: `Arrow`
**filter**<br>*optional* | Icon Filter (`all`, `fill`, `bold` or `thin` - defaults to `all`).<br>**Example Value**: `fill`
**page**<br>*optional* | The page to retrieve (defaults to `1`).<br>**Example Value**: `2`

#### Example Request

##### Request URL:

GET `https://iconmonstr-api.2ix.at/icons/search?query=Arrow&filter=fill`

##### Response:

```javascript
{
	"success": true, // False if an error occurred (to be done)
	"pages": 2, // Amount of pages
	"icons": [
		{
			"slug": "cursor-11-svg", // The slug of the icon (useful for urls)
			"previewImage": "https://cdns.iconmonstr.com/wp-content/assets/preview/2013/96/iconmonstr-cursor-11.png", // Preview image of the icon
			"name": "Arrow 80", // Name of the icon
			"likes": 2812 // Amount of likes
		},
		...
	]
}
```

### GET /icons/popular/

> Get popular icons

#### Resource URL

`https://iconmonstr-api.2ix.at/icons/popular/`

#### Parameters

Parameter                  | Description
---------                  | -----------
**page**<br>*optional* | The page to retrieve (defaults to `1`).<br>**Example Value**: `12`

#### Example Request

##### Request URL:

GET `https://iconmonstr-api.2ix.at/icons/popular?page=12`

##### Response:

```javascript
{
	"success": true, // False if an error occurred (to be done)
	"pages": 75, // Amount of pages
	"icons": [
		{
			"slug": "lock-21-svg", // The slug of the icon (useful for urls)
			"previewImage": "https://cdns.iconmonstr.com/wp-content/assets/preview/2012/96/iconmonstr-lock-21.png", // Preview image of the icon
			"name": "Lock 21", // Name of the icon
			"likes": 7887 // Amount of likes
		},
		...
	]
}
```

### GET /icons/new/

> Get new icons

#### Resource URL

`https://iconmonstr-api.2ix.at/icons/new/`

#### Parameters

Parameter                  | Description
---------                  | -----------
**page**<br>*optional* | The page to retrieve (defaults to `1`).<br>**Example Value**: `3`
**filter**<br>*optional* | Icon Filter (`all`, `fill-bold` or `thin` - defaults to `all`).<br>**Example Value**: `thin`

> Note that other than at the `/icons/search` enpoint, there's only a `fill-bold` (no `fill` or `bold` by it's own)

#### Example Request

##### Request URL:

GET `https://iconmonstr-api.2ix.at/icons/new?page=3&filter=thin`

##### Response:

```javascript
{
	"success": true, // False if an error occurred (to be done)
	"pages": 4, // Amount of pages
	"icons": [
		{
			"slug": "video-thin-svg", // The slug of the icon (useful for urls)
			"previewImage": "https://cdns.iconmonstr.com/wp-content/assets/preview/2018/96/iconmonstr-video-thin.png", // Preview image of the icon
			"name": "Video", // Name of the icon
			"dateInfo": {
				"raw": "9 months ago", // The raw date string
				"count": "9", // The count of the time unit
				"unit": "month", // The time unit (second/minute/day/week/month/year)
				"plural": true // Whether the unit is in plural or not (month/months)
			}
		},
		...
	]
}
```

### GET /icons/:slug/

> Get a specific icon

#### Resource URL

`https://iconmonstr-api.2ix.at/icons/:slug/`

#### Parameters

Parameter                  | Description
---------                  | -----------
**slug**<br>*required* | The URL-friendly name of the icon.<br>**Example Value**: `github-1`
**fileType**<br>*optional* | The file type of the icon (`svg`, `eps`, `psd` or, if available, `font` - defaults to `svg`).<br>**Example Value**: `svg`

#### Example Request

##### Request URL:

GET `https://iconmonstr-api.2ix.at/icons/github-1?fileType=svg`

##### Response:

```javascript
{
	"success": true, // False if an error occurred (to be done)
	"icon": {
		"slug": "github-1", // The slug of the icon (useful for urls)
		"url": "https://iconmonstr.com/github-1-svg", // Url of the icon
		"availableFileTypes": [ // Available file types for the fileType parameter
			"svg",
			"eps",
			"psd",
			"png", // NOT YET IMPLEMENTED!!!
			"font"
		],
		"name": "Github 1", // Name of the icon
		"previewImage": "https://cdns.iconmonstr.com/wp-content/assets/preview/2012/240/iconmonstr-github-1.png", // Preview image of the icon
		"downloadLink": "https://iconmonstr.com/?s2member_file_download_key=b4c27b8119dec7660f7f2adb48227cdc&s2member_file_download=2012/svg/iconmonstr-github-1.svg", // URL to download the icon
		"embedCode": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path d=\"M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z\"/></svg>", // Embed code of the icon (only for svg file type)
		"similar": [ // Similar icons
			"github-4",
			"github-2",
			"github-3",
			"github-5"
		],
		"tags": [ // Icon tags (useful for search)
			"brand",
			"github",
			"logo",
			"multiservice",
			"social"
		],
		"collection": "github" // The collection the icon is in
	}
}
```
