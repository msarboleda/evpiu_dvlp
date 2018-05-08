# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "bento/ubuntu-16.04"
  config.vm.boot_timeout = 600
  config.vm.network "private_network", ip: "192.168.100.100", auto_correct: true
  config.vm.synced_folder "webroot", "/var/www/evpiudvlp", owner: "www-data", group: "www-data"
  config.vm.provider "virtualbox" do |v|
    v.name = "evpiudvlp"
    v.customize ["modifyvm", :id, "--cpuexecutioncap", "80"]
    v.customize ["modifyvm", :id, "--memory", 2048]
    v.customize ["modifyvm", :id, "--cpus", 1]
  end

  if defined?(VagrantPlugins::HostsUpdater)
    config.vm.hostname = "evpiudvlp.local"
    config.hostsupdater.aliases = [
      "www.evpiudvlp.local"
    ]
  end

  # Configurar los locales de Linux en Español - Colombia
  config.vm.provision :shell, :path => "config/locales.sh"
  config.vm.provision :shell, :path => "config/build.sh"
end