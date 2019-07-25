<?php

namespace Paulo\Classes;

final class RepositoryLoader
{
    /**
     * Load repositories without registration.
     *
     * @return void
     */
    public function loadRepositoriesDinamically()
    {
        $newRepos = [];

        $dirs = $this->scanRepositories();

        foreach($dirs as $dir) {
            $newRepos[$this->createKeyClass($dir)] = $this->createValueClass($dir);
        }

        $mergedRepos = array_merge(config('respository.repositories'), $newRepos);

        config(['respository.repositories' => $mergedRepos ]);
    }

    /**
     * Scan repositories folder.
     *
     * @return void
     */
    private function scanRepositories()
    {
        $repoDir = app_path() . '/Repositories';

        return array_filter(scandir($repoDir), function ($folder) {
            return $folder != '.' && $folder != '..';
        });
    }

    /**
     * Generate contract name.
     *
     * @param string $dir name of the repository folder.
     * @return string
     */
    private function createKeyClass(string $dir): string
    {
        return 'App\\Repositories\\' . $dir . '\\' . $dir . 'Contract';
    }

    /**
     * Generate repo name.
     *
     * @param string $dir name of the repository folder.
     * @return string
     */
    private function createValueClass(string $dir): string
    {
        return 'App\\Repositories\\' . $dir . '\\' . $dir;
    }
}
