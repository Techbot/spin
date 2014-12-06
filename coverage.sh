if [ "$TRAVIS_PULL_REQUEST" == "false" ]; then

  git config --global user.email "travis@travis-ci.org"
  git config --global user.name "Travis"

  cp -R coverage $HOME/coverage
  cd $HOME

  git clone --quiet --branch=gh-pages https://${GH_TOKEN}@github.com/assertchris/spin.git gh-pages > /dev/null
  cd gh-pages
  mkdir {$TRAVIS_BRANCH}
  cd {$TRAVIS_BRANCH}

  cp -Rf $HOME/coverage/* .

  git add -f .
  git commit -m "Travis build $TRAVIS_BUILD_NUMBER@$TRAVIS_BRANCH pushed to gh-pages"
  git push -fq origin gh-pages > /dev/null
fi
