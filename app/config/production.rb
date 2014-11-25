set :application, "4success"
set :domain,      "128.199.43.50"
set :user,        "4success"
set :deploy_to,   "/home/4success/4success"

set :branch,      "master"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true