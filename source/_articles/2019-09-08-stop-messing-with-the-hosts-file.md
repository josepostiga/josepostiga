---
extends: _layouts.article
section: content
title: Stop Messing With The Hosts File
date: 2019-09-08
cover_image: https://images.unsplash.com/photo-1465447142348-e9952c393450?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=968&q=80
photo_credits: https://unsplash.com/photos/7nrsVjvALnA
description: "How to leverage Dnsmasq to route all local development traffic to localhost."
---

> This article was originally submitted on the [Infraspeak tech blog](https://medium.com/infraspeak/c).

The hosts file is used by the system’s DNS resolver to map a fully qualified domain name (FQDN) to its related IP, without the need to query any of the Internet’s DNS servers. However, since updating this file, manually, by the common computer user would be impractical, its use, essentially, is to map the local IP 127.0.0.1 to localhost domain so that it resolves to the host machine.

So, since this file overrides the default behavior of querying the Internet’s DNS servers, and directly maps any string of characters to an IP, it’s frequently used by programmers to associate a development domain with the localhost IP address. This enables the use of a non-existent domain like, for example, `my-secret-project.test` as an FQDN on a browser, as you’d normally access a registered .com (or any other TLD) domain.

At Infraspeak, we have several projects that use personalized domain names in our development environment. This requires that each of those domain names have to be mapped in the hosts file of every developer’s computer. Every time we use a new, or change an existent, project domain name, we have to warn everybody about it, and each developer has to update the hosts file.

Since we’re investing a lot in hiring, it got me thinking about the tedious work of every new colleague editing the same file, paste the same mappings, over and over again, and I decided to investigate a way of automating the process. I’ve come across several infrastructure-related projects, in my past, that does this. Valet, for example, which is a Laravel development environment, does this exact thing: it routes all requests for domains that use the `.test` TLD to a preconfigured folder with the same name of the domain requested. So, a request to `my-secret-project.test` would route to a project that would be inside a my-secret-project folder.

I knew that Valet leveraged Dnsmasq to do that, so I went to read more about how that software works and how could I add it to my stack and route the development domains, without editing the hosts file. While following the documentation to install it in my machine, I got an error related to port 53 being already in used. While trying to find which service was using that port, I found that a package named dnsmasq-base was already installed on my Ubuntu machine.

I don’t really know how Ubuntu was using that package for (or if it was really used at all) but I guess that it could be that the NetworkManager was leveraging the awesome capabilities of DNS caching that Dnsmasq is known for. Either way, the fact that it was already installed saved me some steps on configuring the environment to auto-resolve development domains. Now, I just needed to find a way to configure the Ubuntu NetworkManager to use Dnsmasq instead of system-resolve.

A little more time investigating showed me that NetworkManager already supports Dnsmasq out-of-the-box, and that to enable its use, all that's required is a single key in the `[main]` section of its configuration file. That’s simple enough, but I’ve been working with Linux for a lot of time, now, to know that almost every system’s service has a `conf.d` folder that’s used to override the default configuration with the use of partial overriding of variables. Without any surprise, I found that the NetworkManager was no exception to this.

That meant that I simply needed to add a file to the `conf.d` folder with only the configuration needed to activate the Dnsmasq support. I called the file `dnsmasq.conf` and put it inside the folder `/etc/NetworkManager/conf.d`, with the following content:

```
[main]
dns=dnsmasq
```

Is was that simple! Now, the NetworkManager service was being instructed to use Dnsmasq to resolve all domains, instead of the native system-resolve service. However, this configuration alone was insufficient to route all local development domains to the localhost IP address. I still needed to add a specific Dnsmasq configuration file, that instructs it to not query the Internet’s DNS servers for requests of specific TLDs (our development TLDs).

The file that was missing needed to be inside the dnsmasq.d folder, which is very close to the previous folder: `/etc/NetworkManager/dnsmasq.d`. Creating a file, there, with the name `development-tld.conf` and adding a single line with `address=/development/127.0.0.1` is all that was missing. Now, Dnsmasq would not query the Internet’s DNS servers to know what’s the IP for every domain that uses `.development` TLD.

> We use `.development` as a local development TLD, but we could use the exact steps to use any other TLD, like `.test` or `.dev`. Just take note that whatever you use, won’t reach the Internet. So, if you use `.com`, for example, all `.com` domains will try to be resolved to your machine. Stick with a TLD that you know it’s not a “valid” one on the Internet, to avoid such problems.

Finally, I had everything properly configured. I only needed to disable the system-resolve service, permanently, and restart the NetworkManager service. The commands to do so were the following:

```
sudo systemctl disable systemd-resolved.service
sudo systemctl stop systemd-resolved.service
sudo rm /etc/resolv.conf
sudo systemctl restart network-manager.service
```

The line `sudo rm /etc/resolv.conf` forces the O.S. to regenerate the file with the new, updated, configuration, and the last line forces NetworkManager to load the all-new configuration.

Now I had all my development domains auto-resolving to the localhost machine, even with domains that I never configured in the hosts file. Here’s an excerpt of what happens when pinging any domain with the configured `.development` TLD:

```
▶ ping infraspeak.development
PING infraspeak.development (127.0.0.1) 56(84) bytes of data.
64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.025 ms
64 bytes from localhost (127.0.0.1): icmp_seq=2 ttl=64 time=0.038 ms

--- infraspeak.development ping statistics ---
2 packets transmitted, 2 received, 0% packet loss, time 32ms
rtt min/avg/max/mdev = 0.025/0.031/0.038/0.008 ms

▶ ping non-existing-subdomain.infraspeak.development
PING non-existing-subdomain.infraspeak.development (127.0.0.1) 56(84) bytes of data.
64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.027 ms
64 bytes from localhost (127.0.0.1): icmp_seq=2 ttl=64 time=0.041 ms

--- non-existing-subdomain.infraspeak.development ping statistics ---
2 packets transmitted, 2 received, 0% packet loss, time 20ms
rtt min/avg/max/mdev = 0.027/0.034/0.041/0.007 ms

▶ ping customer-x.infraspeak.development            
PING customer-x.infraspeak.development (127.0.0.1) 56(84) bytes of data.
64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.025 ms
64 bytes from localhost (127.0.0.1): icmp_seq=2 ttl=64 time=0.045 ms

--- customer-x.infraspeak.development ping statistics ---
2 packets transmitted, 2 received, 0% packet loss, time 23ms
rtt min/avg/max/mdev = 0.025/0.035/0.045/0.010 ms
```

Pretty sweet, right? No more messing around the hosts file!
