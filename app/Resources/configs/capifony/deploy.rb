# See http://capifony.org/ for more details
set :application, "4success"
set :stages, %w(qa)
set :default_stage, "qa"
set :stage_dir, "app/Resources/configs/capifony/stages"
set :deploy_to, "/var/www/#{application}"
set :app_path, "app"
set :web_path, "web"

# default_run_options[:pty] = true

# Repository should be set via command line parameter: -s repository=path/to/tar.gz
# set :repository, "path/to/tar.gz"
set :scm, :none
set :deploy_via, :archive
set :user, "www-sh"
set :use_sudo, false
set :keep_releases, 3
set :shared_files, ["app/config/parameters.yml"]
set :shared_children, [app_path + "/logs"]
set :group_writable, true
set :interactive_mode, false

# SSH options
# ssh_options[:forward_agent] = true
# ssh_options[:keys] = [File.join(ENV["HOME"], ".ssh", "KEY FILE NAME")]

set :maintenance_basename, "maintenance"
set :maintenance_template_path, "app/Resources/configs/capifony/maintenance.html.erb"
set :model_manager, "doctrine"
set :use_composer, false
set :update_vendors, false
set :dump_assetic_assets, false
set :assets_install, false
set :clear_controllers, false

# Service name which should be restarted on app servers (e.g. php5-fpm, apache2)
set :app_service_name, "apache2"

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

# Directory with parameters_*.yml files
set :parameters_dir, "app/config"
set :parameters_file, false

# DB Dump config
set :database_type, "mysql" #Supported types: mysql, pgsql
set :database_host, ""
set :database_user, ""
set :database_password, ""
set :database_name, ""

require 'capistrano/ext/multistage'

# Comment if you don't like to restart App / Web server before warming up cache
before "symfony:cache:warmup", "deploy:restart"

# Comment if you'd like not to use Doctrine Migrations
before "symfony:cache:warmup", "symfony:doctrine:migrations:dump_status"
before "symfony:cache:warmup", "deploy:migrate"

# Comment if you'd like not to copy parameters.yml file
before 'deploy:share_childs', 'deploy:upload_parameters'

# Custom tasks
namespace :deploy do
    desc "Start Application service"
    task :start, :except => { :no_release => true }, :roles => :app do
        run "sudo service #{app_service_name} start"
        puts "--> #{app_service_name} successfully started".green
    end

    desc "Stop Application service"
    task :stop, :except => { :no_release => true }, :roles => :app do
        run "sudo service #{app_service_name} stop"
        puts "--> #{app_service_name} successfully stopped".green
    end

    desc "Restart Application service"
    task :restart, :except => { :no_release => true }, :roles => :app do
        run "sudo service #{app_service_name} restart"
        puts "--> #{app_service_name} successfully restarted".green
    end

    desc "Fetch currently deployed version of an application into local file"
    task :dump_current_version, :roles => :app, :except => { :no_release => true }, :only => { :primary => true } do
        filename = "app-version-#{Time.now.to_i}.txt"
        file = "#{latest_release}/version.txt"
        FileUtils.mkdir_p("backups")
        begin
            get file, "backups/previous-version.txt"
        rescue Exception
        end
    end

    task :upload_parameters do
        origin_file = parameters_dir + "/" + parameters_file if parameters_dir && parameters_file
        if origin_file && File.exists?(origin_file)
            ext = File.extname(parameters_file)
            relative_path = "app/config/parameters" + ext

            if shared_files && shared_files.include?(relative_path)
                destination_file = shared_path + "/" + relative_path
            else
                destination_file = latest_release + "/" + relative_path
            end
            try_sudo "mkdir -p #{File.dirname(destination_file)}"

            top.upload(origin_file, destination_file)
            puts "--> #{destination_file} successfully deployed".green
        end
    end
end

namespace :symfony do
    namespace :doctrine do
        namespace :migrations do
            desc "Fetch the status of Doctrine Migrations into local file"
            task :dump_status, :roles => :app, :except => { :no_release => true }, :only => { :primary => true } do
                filename = "doctrine-migrations-#{Time.now.to_i}.txt"
                file = "#{remote_tmp_dir}/#{filename}"
                begin
                    data = capture("#{try_sudo} sh -c 'cd #{latest_release} && #{php_bin} #{symfony_console} doctrine:migrations:status --show-versions > #{file}'")
                    puts data
                    FileUtils.mkdir_p("backups")
                    get file, "backups/previous-doctrine-migrations-info.txt"
                rescue Exception
                end
            end
        end
    end
end


namespace :database do
    namespace :dump do
    desc "Dumps remote database"
        task :remote, :roles => :db, :only => { :primary => true } do
            env       = fetch(:deploy_env, "remote")
            filename  = "#{application}.#{env}_dump.#{Time.now.utc.strftime("%Y%m%d%H%M%S")}.sql.gz"
            file      = "#{remote_tmp_dir}/#{filename}"

            case #{database_driver}
            when "mysql"
                data = capture("#{try_sudo} sh -c 'mysqldump --opt --single-transaction -u#{database_user} --host='#{database_host}' --password='#{database_password}' #{database_name} | gzip -c > #{file}'")
                puts data
            when "pgsql"
                data = capture("#{try_sudo} sh -c 'PGPASSWORD=\"#{database_password}\" pg_dump -U #{database_user} #{database_name} -h#{database_host} --clean | gzip -c > #{file}'")
                puts data
            end

            FileUtils.mkdir_p("#{backup_path}")

            capifony_progress_start
            get(file, "#{backup_path}/#{filename}", :via => :scp) do |channel, name, sent, total|
                capifony_progress_update(sent, total)
            end

            begin
                FileUtils.ln_sf(filename, "#{backup_path}/#{application}.#{env}_dump.latest.sql.gz")
            rescue Exception # fallback for file systems that don't support symlinks
                FileUtils.cp_r("#{backup_path}/#{filename}", "#{backup_path}/#{application}.#{env}_dump.latest.sql.gz")
            end
            run "#{try_sudo} rm -f #{file}"
        end
    end
end
