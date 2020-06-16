<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Pages\Models;

use NF\NeoFrag\Loadables\Model;

class Pages extends Model
{
	public function get_pages()
	{
		return $this->db->select('p.page_id', 'p.name', 'p.published', 'pl.title', 'pl.subtitle')
						->from('nf_pages p')
						->join('nf_pages_lang pl', 'p.page_id = pl.page_id')
						->where('pl.lang', $this->config->lang->info()->name)
						->order_by('pl.title ASC')
						->get();
	}

	public function check_page($page_id, $title, $lang = 'default', $all = FALSE)
	{
		if ($lang == 'default')
		{
			$lang = $this->config->lang->info()->name;
		}

		$this->db	->select('p.*', 'pl.title', 'pl.subtitle', 'pl.content')
					->from('nf_pages p')
					->join('nf_pages_lang pl', 'p.page_id = pl.page_id')
					->where('p.page_id', $page_id);

		if (!$all)
		{
			$this->db->where('p.published', TRUE);
		}

		$page = $this->db	->where('pl.lang', $lang)
							->row();

		if ($page && url_title($page['title']) == $title)
		{
			return $page;
		}
		else
		{
			return FALSE;
		}
	}

	public function add_page($name, $title, $published, $subtitle, $content)
	{
		$page_id = $this->db->insert('nf_pages', [
			'name'           => $name ?: url_title($title),
			'published'      => $published
		]);

		$this->db->insert('nf_pages_lang', [
			'page_id'        => $page_id,
			'lang'           => $this->config->lang->info()->name,
			'title'          => $title,
			'subtitle'       => $subtitle,
			'content'        => $content
		]);

		$this->access->init('pages', 'page', $page_id);
	}

	public function edit_page($page_id, $name, $title, $published, $subtitle, $content, $lang)
	{
		if (!$this->db	->from('nf_pages p')
						->join('nf_pages_lang l', 'p.page_id = l.page_id')
						->where('p.page_id', $page_id)
						->where('l.lang', $lang)
						->empty())
		{
			$this->db	->where('page_id', $page_id)
						->where('lang', $lang)
						->update('nf_pages_lang', [
							'title'    => $title,
							'subtitle' => $subtitle,
							'content'  => $content
						]);

			$this->db	->where('page_id', $page_id)
						->update('nf_pages', [
							'name'           => $name ?: url_title($title),
							'published'      => $published
						]);
		}
		else
		{
			$this->db	->insert('nf_pages_lang', [
							'page_id'  => $page_id,
							'lang'     => $lang,
							'title'    => $title,
							'subtitle' => $subtitle,
							'content'  => $content
						]);

			$this->db	->where('page_id', $page_id)
						->update('nf_pages', [
							'name'           => $name ?: url_title($title),
							'published'      => $published
						]);
		}
	}

	public function delete_page($page_id)
	{
		$this->db	->where('page_id', $page_id)
					->delete('nf_pages');

		$this->access->delete('pages', $page_id);
	}
}
