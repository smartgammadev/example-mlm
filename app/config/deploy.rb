set :application, "4success.smart-gamma.com"
set :domain,      "4success.smart-gamma.com"
set :user,        "4success"
set :deploy_to,   "/home/4success/4success"
set :app_path,    "app"

set :repository,  "git@bitbucket.org:stas81/4success.git"
set :scm,         :git
set :branch,      "master"

set :model_manager, "doctrine"
set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]


set :use_composer, true
set :use_sudo,    false

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true

set  :keep_releases,  5

logger.level = Logger::MAX_LEVEL

# Be more verbose by uncommenting the following line
 logger.level = Logger::MAX_LEVEL