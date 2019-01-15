FROM bylexus/apache-php7
MAINTAINER BuRner <burner@live.be>

RUN apt-get update && \
    apt-get install --no-install-recommends -y \
	php7.0-intl \
	php7.0-curl \
	&& rm -rf /var/lib/apt/lists/*