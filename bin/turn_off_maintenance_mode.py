import boto3
import socket
import os
import sys
import commands
import time

aws_region_name = 'eu-west-1' #ap-southeast-1
ec2 = boto3.resource('ec2', region_name=aws_region_name)
ssh_port = '22'
ssh_user = 'root'

def getInstanceId(instance):
    return instance['InstanceId']

def getAppInstanceRunning(ip):
    return ec2.instances.filter(Filters=[{'Name': 'ip-address', 'Values': [ip]}])


def getAPIInstanceRunning(auto_scaling_group_name):
    print '========== getAPIInstanceRunning =========='
    client = boto3.client('autoscaling', region_name=aws_region_name)
    response  = client.describe_auto_scaling_groups(
        AutoScalingGroupNames=[
        auto_scaling_group_name,
        #'bce-prod-api-as-group',
    ])
    group = response['AutoScalingGroups'][0]

    instanceIds = [getInstanceId(i) for i in group['Instances']]
    runningInstances = ec2.instances.filter(InstanceIds=instanceIds)

    return runningInstances

def turnOffMaintenanceInInstance(instance, file):
    publicIp = instance.public_ip_address
    privateIp = instance.private_ip_address
    print 'publicIp: ' + publicIp
    print 'privateIp:' + privateIp
    host = ssh_user + '@' + publicIp

    cmd = 'ssh -p ' + ssh_port + ' ' + host + ' "cd /var/www/' + file + ' && php artisan up"'
    print '========== run cmd =========='
    print cmd
    value_cmd = os.system(cmd)
    print('value_cmd:', value_cmd)
    print 'done------turnOffMaintenanceInInstance'


if __name__ == '__main__':
   print '========== START =========='
   app_ip= sys.argv[1]
   auto_scaling_group_name= sys.argv[2]

   print "app_ip:" + app_ip
   print "auto_scaling_group_name:" + auto_scaling_group_name
   runningAppInstances = getAppInstanceRunning(app_ip)
   runningApiInstances = getAPIInstanceRunning(auto_scaling_group_name)

   for instance in runningApiInstances:
       print '==========runningApiInstances: turn off maintenace mode ' + instance.id + ' START =========='
       turnOffMaintenanceInInstance(instance, 'bitcastle_backend')

   for instance in runningAppInstances:
       print '==========runningAppInstances: turn off maintenace mode ' + instance.id + ' START =========='
       turnOffMaintenanceInInstance(instance, 'bitcastle_frontend')

   print '========== END =========='
