Summary: Enterprise Management Platform
Name: emp
Version: 3.3.0
Release: 0
Vendor: zed-3, Inc 2003-2014
License: zed-3, Inc 2003-2014
Group: Applications/ASG
BuildRoot: /tmp/BUILD/EMP
%description
This package contains the web files for Enterprise Management Platform

%pre 
if [ "$1" = "2" ]; then 
    if [ -e /usr/local/emp/private/config/language.ini ]; then 
        /bin/cp /usr/local/emp/private/config/language.ini /tmp/emp_language.ini
    fi 
fi 


%post
if ! [ -d /usr/local/asg/www/html ];
then
    /bin/mkdir -p /usr/local/asg/www/html
fi

if ! [ -L /usr/local/asg/www/html/emp ];
then
    /bin/ln -s /usr/local/emp/www /usr/local/asg/www/html/emp
fi

if ! [ -L /usr/local/emp/runtime ];
then
    /bin/ln -s /var/emp/runtime /usr/local/emp/runtime
fi

if [ -d /usr/local/emp/www/files ];
then
    rm -rf /usr/local/emp/www/files
fi

if ! [ -L /usr/local/emp/www/files ];
then
    /bin/ln -s /var/emp/upload /usr/local/emp/www/files
fi

chown apache.apache -R /usr/local/emp
rm -f /var/emp/runtime/cache/*

if [ -x /etc/init.d/httpd ];
then
    /etc/init.d/httpd restart >/dev/null 2>&1
fi

if [ "$1" = "2" ]; then 
    if [ -e /tmp/language.ini ]; then 
        /bin/mv /tmp/emp_language.ini /usr/local/emp/private/config/language.ini
    fi 
fi 

%postun

if [ "$1" = "0" ]; then
    if [ -L /usr/local/asg/www/html/emp ];
    then
        rm -f /usr/local/asg/www/html/emp
    fi

    if [ -d /usr/local/emp ];
    then
        rm -rf /usr/local/emp
    fi

    if [ -d /var/emp ];
    then
        rm -rf /var/emp
    fi
fi

%files
%defattr (-,root,root)
/usr/local/emp
/var/emp
