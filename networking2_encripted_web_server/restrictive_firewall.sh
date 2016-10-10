#!/bin/bash

# Printing iptables firewall records before settin up the firewall
echo
echo '=================================================================='
echo
echo 'iptables firewall records before setting up the firewall:'
echo
echo '=================================================================='
sudo iptables -L -v
echo

# logging al ldropped packets
# logging al ldropped packets

sudo iptables -N INPUT
sudo iptables -A INPUT -j INPUT
sudo iptables -A INPUT -m limit --limit 2/min -j LOG --log-prefix "IPTables-Dropped: " --log-level 4

# Allow established session
sudo iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT

# Allow SSH connection eventhough everyting is blocked

#append this rule to the input chain (-A INPUT) so we look at incoming traffic
#check to see if it is TCP (-p tcp).
#if so, check to see if the input goes to the SSH port (--dport ssh).
#if so, accept the input (-j ACCEPT).
sudo iptables -A INPUT -p tcp --dport ssh -j ACCEPT

# Allow web traffic (TCP:80)
#sudo iptables -A INPUT -p tcp --dport 80 -j


# Once a decision is made to accept a packet, no more rules affect it.
# As our rules allowing ssh and web traffic come first, as long as our rule to block all traffic comes after them, we can still accept the traffic we want.
# All we need to do is put the rule to block all traffic at the end.

# BLOCKING ALL OTHER TRAFFIC

sudo iptables -A INPUT -j DROP

# Add allowing the loop back to the beginning of firewall tabel for loopback (127.0.0.1)

sudo iptables -I INPUT 1 -i lo -j ACCEPT

# Print the iptables firewall table after adding the policies
echo
echo '=================================================================='
echo
echo 'iptables firewall records after setting up the firewall: '
echo
echo '=================================================================='
echo
sudo iptables -L -v
