<?php 
class Semver {

	public function gt($v1, $v2){
		return Semver\Comparator::greaterThan($v1, $v2);
	}

	public function lt($v1, $v2){
		return Semver\Comparator::lessThan($v1, $v2);
	}

	public function sort(array $v1){
		return Semver\Semver::sort($v1);
	}

	public function rsort(array $v1){
		return Semver\Semver::rsort($v1);
	}

	public function satisfies($v1, $v2){
		$v2 = str_replace('^ ', '^', $v2);
		return Semver\Semver::satisfies($v1, $v2);
	}

	public function satisfiedBy(array $v1, $v2){
		$v2 = str_replace('^ ', '^', $v2);
		return Semver\Semver::satisfiedBy($v1, $v2);
	}

}