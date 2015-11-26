FROM centos:7

RUN rpm --rebuilddb
#RUN yum update -y
RUN yum install -y yum-utils wget patch tar bzip2 unzip
RUN yum install -y epel-release
RUN rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
RUN yum-config-manager -q --enable remi
RUN yum-config-manager -q --enable remi-php56
RUN yum install -y php-cli php-bcmath php-gd php-intl php-mbstring php-pecl-imagick php-mcrypt php-mysql php-opcache php-pdo php-pecl-redis php-pecl-yaml
