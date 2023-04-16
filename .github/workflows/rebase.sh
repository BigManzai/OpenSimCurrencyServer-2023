datetime=$(date +'%Y-%m-%d-%H-%M-%S')
release_name="release-${datetime}"

git tag -a ${release_name} -m ${release_name}
git push origin ${release_name}
