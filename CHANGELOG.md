## [Unreleased]
### Added
- Nighly changes here


#10.45.4 2025-01-14
- Optimization for mousewheel zoom for large floorplans
- Converter updated to ax2023_L64_23_12_146



#10.10.7 2024-09-17 
- Error when repetitive saved and load redlines and change floorplan
- Error when saving and loading redlines and then attempt to move, rotate, scale a redline


#10.10.4 2024-09-16
- Due to issues running CAD converter under snap, we rolled back to a previous dwg toolkit, converter ax2023_L64_23_12_140 added,
- Issues on v28 onwards with redlines load and save. They will overload the canvas when changing floorplans. 
- Redlines set style and thickness would affect order of redlines, depending how the modal is escaped.


#10.9.5 2024-09-08
- Admin panel Api conversion log would not appear after v10.3.1 implementation of reverse proxy.


#10.7.6 2024-09-04
- Fix for nextcloud v28/v29 preventing opening of CADViewer right side pane for nextcloud collaboration tools


#10.4.1  2024-08-28
- updated executable for AIO Alpine support
- ax2025_L64_25_07_140e added, see: https://github.com/CADViewer/NextCloud/blob/main/README.md#5-integration-in-nextcloud-aio-docker-setup , with glib installed executable runs on Alpine


#10.3.3   2024-08-22
- issue with reverse proxy setting, with /apps/ folder installation.

#10.3.1   2024-08-21
- reverse proxy for /apps/ custom_apps/ extra_apps folder



#10.1.11  2024-08 16
- update for custom_apps folder


# 9.90.10 2024-08-10
- update to include easy access to full 10 days license keys
- updates to redlines interactivy
- back-end converter updated to v23.12.134



# 9.71.1 2024-06-09
- fixes for zoom, redline internal handling, multicanvas, not carried into nextcloud instance yet. 


# 9.50.1 2024-04-17
- update to automatically set the rewrite condition in .htaccess file, if installed under ubuntu/snap



### 9.47.1 2024-02-19
- update to automatically set the rewrite condition in .htaccess file
- information message id .htaccess is not properly formatted
- update for menu file setting
- top menu loading coded independent on server side connectivity (.htaccess php settings)


### 9.44.2 2024-02-11
- bug fix, breaking change in NextCloud 28 for fileloads, scope issue of replaceAll()
- right side icon highlights
- redline update


### 9.40.6 2024-01-29
- Birdseye update
- gitkeep added to conversion/files/ folder and sub-folders


### 9.38.6 2024-01-22
- Updates of Redline text insertion for birdseye and pdf print controls
- AutoXchange v23.12.128 updated

### 9.37.4 2024-01-18
- css styling updates
- Redline text insertion update

### 9.36.4 2024-01-17
- Update for Nextcloud 28 support
- New redline move, rotate, scale commands

### 9.25.9 2023-12-27
- Functional update for compare file versions
- Update of CADViewer bug fixes


### 9.23.2 2023-12-07
- Functional update for compare versions
- Admin interface changes to control cached conversions
- Admin interface changes to provide licensing information if converter not running
- CADViewer bug fixes

### 9.20.5 2023-12-01
- Functional update for compare versions (not released)

### 9.19.6 2023-11-24
- Documentation update
- Scripts update
- Default message at conversion log, for user verification


### 9.19.4 2023-11-23
- Optimized CADViewer for vulnerabilities
- Implemented TTF handling in AutoXchange when running under Snap
- AutoXchange v23.12.124m added


### 9.18.8 2023-11-20
- Removed excessive /fonts collections
- AutoXchange v23.12.124d added

### 9.18.4 2023-11-20
- Changes to php controlling script for Snap
- AutoXchange v23.12.124c added


### 9.18.2 2023-11-17
- Fixed redline text bug
- Fixed issue when installing via Snap on Ubuntu, nextcloud now uses extra-apps folder for external applications


### 9.17.2 2023-11-15
- Fixed PDF creation bug


### 8.88.5  2023-09-12

- Update how url for assets is genearated to support installation in extra_apps folder
- Added AX version v23.12.120
- Updated Space Object processing
- Updated Sticky Notes editing interface


### 8.80.6 2023-08-09
### Fixed
- StickyNotes insert issues after deletion, save and load. 

### 8.78.2 2023-07-23
### Added
- License controls of small number of users 


### 8.77.1 - 2023-07-04
### Added
- Support for NextCloud version 27

### 8.76.1 - 2023-06-27
### Added
- definition of CADViewer group for small batches of licensee


### 8.75.5 - 2023-06-20
### Added
- granular ability to control folders for parameter settings
- AutoXchange v23.12.115 added



### 8.72.2 - 2023-06-05
### Added
- ability to control user folders for parameter settings
- AutoXchange v23.12.114e added


### 8.70.4 - 2023-05-25
### Added
- ability to set conversion back-end and front-end parameters on a Folder basis



### 8.67.18  - 2023-05-17
### Fixed
- compare method improved graphics
- retaining license keys over upgrades



### 8.66.4  - 2023-05-12
### Fixed
- compare method logics
- strokea for horizontal text elements
- zoom wheel issue
- lineweight controls in admin pane
### Added
- AutoXchange ax2023_L64_23_12_114a



### 8.65.4 - 2023-05-10
### Fixed
- API call for lineweight modifications
- Compare method for multiple languages
### Added
- AutoXchange ax2023_L64_23_12_113g
- es_isocp.shx



### 8.63.3 - 2023-05-03
### Added
- Localization of CADViewer menu system, support for: English, German, Spanish, French, Spanish, Portuguese, Korean, Chinese Simplified, Chinese Traditional and Indonesian. 
- compare method updated
- AutoXchange ax2023_L64_23_12_113b

### 8.61.4 - 2023-04-26
### Added
- API interface to enable dynamic change of conversion parameters
- AutoXchange ax2023_L64_23_12_112b

### 8.59.19  - 2023-04-19
### Fixed
- issue with nextcloud 26.0.0 installations fixed


### 8.59.18 -  2023-04-16
### Fixed
- template for multiple icon skins
- CSP implemented
- ability to load drawings with + in filename
- AutoXchange ax2023_L64_23_12_112 


### 8.54.1 - 2023-03-21
### Fixed
-cache issue for same named files in two different folders


### 8.53.1 - 2023-03-20
### Fixed
- cache issue for same named file in two different folders


### 8.52.4 - 2023-03-16
### Changed
- AutoXchange ax2023_L64_12_12_110 update
### Fixed
- Left pane file selection issue in NextCloud due to external package overlap
- Print issue in 25.0.4
- cached printing
- external drive loading of files
- MicroStation  .dgn files with EU (non US letter) filenames and Unicode file names


### 8.49.2 - 2023-03-14
### Fixed
- resize issue after component is closed

### 8.47.4 - 2023-03-14
### Fixed
- Navigation between unicode layouts


### 8.46.8 - 2023-03-13
### Fixed
- Change of navigation modal styling and page change issues
- AutoXchange ax2023_L64_23_12_109b update


### 8.45.3 - 2023-03-11
### Fixed
- Css encapsulation
- integrated share and comments on canvas



### 8.44.5 - 2023-03-09
### Fixed
- Dynamic Lineweight in step of 0.1
- zoom-in cursor for window selection
- AutoXchange ax2023_L64_23_12_109 installed


### 8.42.90 - 2023-03-06
### Fixed
- Dynamic lineweight for all linetypes, zoom level and page canvas resize


### 8.40.2 - 2023-03-02
### Fixed
- Layer list selection in nested front-end application folders
- Removed redundant NextCloud event listeners

### 8.37.5 - 2023-02-26
### Added
- load/save checks in php files

### 8.37.5 - 2023-02-25
### Fixed
- Ability to load SVG/SVGZ directly when in Docker container


### 8.36.3 - 2023-02-23
- Debug statements added


### 8.35.1 - 2023-02-22
### Fixed
- Extents of drawing overlapping top toolbar. 

### 8.34.1 - 2023-02-21
### Fixed
- Markup folder have local scope only. Created only on request.
- Direct load of SVG files


### 8.32.3 - 2023-02-18
### Fixed
- Tooltip on integrated help icon selection

## 8.30.20 - 2023-02-17
### Added
- Integrated help icon
### Fixed
- Fileloader location on second load



## 8.27.2 - 2023-02-12
### Added
- Compare method for drawings
- Integrated fileloader in CADViewer
- CADViewer Doctor admin tool


## 8.22.2  - 2023-02-07
### Added
- Cached conversions of drawings





## 8.17.3 – 2023-01-31
### Added
- Ability to add custom file name when saving to CADViewer - Markup folder

### Changed
- Updated to CAD Converter AX2023 v23.12.107a 


### Fixed

### Removed

### Deprecated

### Security



## 8.12.2 – 2023-01-25
### Added
- All core CADViewer functionality on a single page-change menu
- Specific NextCloud CADViewer-Markup global folder for shared collaboration on redlined PDF images of CAD files.
- CAD Converter AutoXchange  AX2023
- Version follows CADViewer version

### Changed

### Fixed

### Removed

### Deprecated

### Security
