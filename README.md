[![Build Status](https://secure.travis-ci.org/fiunchinho/CodingPride.png)](http://travis-ci.org/fiunchinho/[CodingPride])
# Gamification for your development process
CodingPride is a library that you can install in two simple steps, and it will start giving badges to people comitting to a certain repository, based on the badges that you define. For example, you could get badges for reaching 1000 commits with your username; or you could get the **Necromancer** badge for doing changes to a file that was without changes for one year.

# Previous configuration
You need to install [Mongodb](http://docs.mongodb.org/manual/tutorial/install-mongodb-on-linux/), [PHP 5.3 or above](http://www.php.net/downloads.php#v5) and the [mongodb driver](http://php.net/manual/en/mongo.installation.php).

# So... how do I use this thing?
To start using CodingPride you just need to follow two simple steps. If you prefer to ignore old commits from your repository, you can skip step 1
### 1. Import old commits from your repository
CodingPride saves in its database all the commits that you and your colleagues have made since the repository was created. This way, it can give badges using this historical data. To import these commits, you just have to run the following command:

```
./CodingPride badges:install
```

Be aware! CodingPride will try to fetch ALL THE COMMITS that have been made until the very moment you execute that command, so if your repository has a high number of commits, this will take a while. Most of the times, your repository will contain a higher number of commits, than the allowed API rate limit. For example, Github limits its API usage to 5000 requests per hour. This means that if your repository has more than 5000 commits, CodingPride won't be able to fetch them in only one execution. To finish the process, execute this command every 60 minutes until it says it is done importing commits. To make it easier, you can just program a cron job in your machine to execute this process every X minutes. Don't worry, once it finishes the process, nothing happens if you run it again.


### 2. Start giving badges
To start giving badges, you just need to create a new cron job in your machine with the following command:

```
./CodingPride badges:give
```

It will give badges based on the historical commits that you imported in step one, until it's up to date with your repository. It will also fetch the latest commits from your repository and give badges based on these commits. Nothing happens if you execute this process several times, it won't give the same badges again.

# How to create my own badges
Coming soon...

# Further reading
[Crontab quick reference](http://www.adminschoice.com/crontab-quick-reference)
