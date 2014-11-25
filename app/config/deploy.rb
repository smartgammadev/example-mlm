set :application, "4success"
set :domain,      "128.199.43.50"
set :user,        "4success"
set :deploy_to,   "/home/4success/4success"
set :app_path,    "app"

set :repository,  "git@gitlab.smart-gamma.com:4success/4success.git"
set :scm,         :git
set :branch,      "master"

set :model_manager, "doctrine"
set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]


set :use_composer, true
set :use_sudo,    false

set :use_set_permissions,        true
set :webserver_user,             "www-data"
set :permission_method,          :acl

set :writable_dirs,              ["app/cache", "app/logs"]

require 'capistrano/maintenance'
set :maintenance_basename, "maintenance"
set :maintenance_template_path, "app/Resources/configs/capifony/maintenance.html.erb"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true

set  :keep_releases,  5

namespace :symfony do
  desc "Clear apc cache"
  task :apc_clear do
    capifony_pretty_print "--> Clear apc cache"
    run "#{try_sudo} sh -c 'cd #{current_path} && #{php_bin} #{symfony_console} apc:clear --env=#{symfony_env_prod}'"
    capifony_puts_ok
  end
end

set :maintenance_basename, "maintenance"
set :maintenance_template_path, "app/Resources/configs/capifony/maintenance.html.erb"


logger.level = Logger::MAX_LEVEL

# Be more verbose by uncommenting the following line
 logger.level = Logger::MAX_LEVEL

after "deploy", "symfony:apc_clear"
after "deploy:rollback:cleanup", "symfony:apc_clear"