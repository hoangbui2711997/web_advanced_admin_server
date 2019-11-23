app_ip=$1
auto_scaling_group_names=$2

ssh root@3.0.112.179 "cd /root/staging/deploy_admin && python turn_off_maintenance_mode.py ${app_ip} ${auto_scaling_group_names}"

php /var/www/bitcastle_admin/artisan maintenance_mode_setting_notify:send
