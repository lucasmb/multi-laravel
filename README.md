<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

# Introduction

This repo works as multi-site or multi-tenant laravel 6 site
It will work as SaaS, with only one instance of laravel running serving multiple sites each one with its own Database.

It supports subdomain and independent domain site separation
with a dedicated DB per site.

It's based on and  laracon 2017
[Tom Schlick's](https://www.youtube.com/watch?v=T-gHOXFpZvg) Conference at Laracon 2017
and the great multitenant article written by 
[Ollie Read](https://ollieread.com)

#Installation
- Clone this repo to your working folder

      git clone https://github.com/lucasmb/multi-laravel.git
    
- Install packages with composer

      composer install    
      
#####Folder Permissions

- add your web server user to group to the Project Folder

      sudo chown -R :http multi-laravel  

- "storage" Group Writable (Group, User Writable)

      sudo chmod -R gu+w multi-laravel/storage


- "bootstrap/cache" Group Writable (Group, User Writable)

      sudo chmod -R gu+w multi-laravel/bootstrap/cache
      

#####Run migrations and configure sites

- run the main site migrations

      php artisan migrate

- configure your sites in "sites" table, by adding a domain or subdomain and a code. (the code field) will be append to a 'site_' prefix to name each site DB. Also you can add the domains or subdomains to your local /etc/hosts file 

- After adding the sites to the main DB you can deploy them with:

      http://your_site_domain_or_subdomain.{MAIN_DOMAIN}.com/api/deploysite
      
  This will create a new DB for the site and will run all the migrations inside /database/migrations/sites 
