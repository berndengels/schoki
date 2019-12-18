<?php
/**
 * SettingsEntity.php
 *
 * @author    Bernd Engels
 * @created   15.06.19 17:04
 * @copyright Webwerk Berlin GmbH
 */

namespace App\Entities\Newsletter;

use App\Entities\Entity;

/**
 * Class SettingsEntity
 */
class SettingsEntity extends Entity {

	/**
	 * @var string
	 */
	public $subject_line = 'Schokoladen Events';
	/**
	 * @var string
	 */
	public $title = 'Schokoladen Events';
	/**
	 * @var string
	 */
	public $from_name = 'Schokoladen Berlin-Mitte';
	/**
	 * @var string
	 */
	public $reply_to = 'newsletter@schokoladen-mitte.de';
	/**
	 * @var null
	 */
	public $template_id = NULL;
	/**
	 * @var bool
	 */
	public $auto_footer = TRUE;

	/**
	 * @var bool
	 */
	public $inline_css = TRUE;

	/**
	 * @return boolean
	 */
	public function getInlineCss() {
		return $this->inline_css;
	}

	/**
	 * @return boolean
	 */
	public function getAutoFooter() {
		return $this->auto_footer;
	}

	/**
	 * @return mixed
	 */
	public function getSubjectLine() {
		return $this->subject_line;
	}

	/**
	 * @param mixed $subject_line
	 * @return $this
	 */
	public function setSubjectLine($subject_line) {
		$this->subject_line = $subject_line;
		return $this;
	}

	/**
	 * @param boolean $auto_footer
	 * @return $this
	 */
	public function setAutoFooter($auto_footer) {
		$this->auto_footer = $auto_footer;
		return $this;
	}

	/**
	 * @param boolean $inline_css
	 * @return $this
	 */
	public function setInlineCss($inline_css) {
		$this->inline_css = $inline_css;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param mixed $title
	 * @return $this
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFromName() {
		return $this->from_name;
	}

	/**
	 * @param mixed $from_name
	 * @return $this
	 */
	public function setFromName($from_name) {
		$this->from_name = $from_name;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getReplyTo() {
		return $this->reply_to;
	}

	/**
	 * @param mixed $reply_to
	 * @return $this
	 */
	public function setReplyTo($reply_to) {
		$this->reply_to = $reply_to;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getTemplateId() {
		return $this->template_id;
	}

	/**
	 * @param mixed $template_id
	 * @return $this
	 */
	public function setTemplateId($template_id) {
		$this->template_id = $template_id;
		return $this;
	}
}