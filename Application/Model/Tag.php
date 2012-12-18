<?php

namespace Application\Model;

use Framework\Model;

class Tag extends Model
{
	// id, tag
	public function selectTagByID ($id)
	{
		return $this->database->selectOne("SELECT * FROM `tbl_tags` WHERE `id` = ?", array($id));
	}

	// id, tag, class
	public function selectTagByIDWithClass ($id, Model $gallery_model)
	{
		$data = $this->selectTagByID($id);

		$count = $gallery_model->countPicByTag($data['tag']);
		$data['class'] = $this->tagClass($count);

		return $data;
	}

	// array: id, tag
	public function selectAllTags ()
	{
		return $data = $this->database->selectMany("SELECT * FROM `tbl_tags`");
	}

	// array: id, tag, class
	public function selectAllTagsWithClass (Model $gallery_model)
	{
		$data = $this->selectAllTags();

		foreach ($data as &$row)
		{
			$count = $gallery_model->countPicByTag($row['tag']);
			$row['class'] = $this->tagClass($count);
		}
		unset($row);

		return $data;
	}

	// array: id, tag, class, one string of pictures
	public function selectAllTagsWithPicInString (Model $gallery_model)
	{
		$data = $this->selectAllTagsWithClass($gallery_model);

		foreach ($data as &$row) { $row['pictures'] = $gallery_model->selectPicsInStringByTag($row['tag']); }
		unset($row);

		return $data;
	}

	// array: id, tag, pictures
	public function selectAllTagsWithPics (Model $gallery_model)
	{
		$data = $this->selectAllTags();

		foreach ($data as &$row) { $row['pictures'] = $gallery_model->selectPicsByTag($row['tag']); }
		unset($row);

		return $data;
	}

	// array: id, tag
	public function selectTagsByPicID ($picture_id)
	{
		return $this->database->selectMany
		("
			SELECT t.id, t.tag FROM `tbl_pictures_tags` AS `pt`
			LEFT JOIN `tbl_tags` AS `t`
			ON pt.tags_id = t.id
			WHERE pt.pictures_id = ?
		", array($picture_id));
	}

	// one string of tags
	public function selectAllTagsInString ()
	{
		$data = $this->selectAllTags();

		$tags_string = '';
		$count = sizeof($data);

		for ($i = 0; $i < $count; $i++)
		{
			if ($i != $count - 1)
			{
				$tags_string .= $data[$i]['tag'] . ', ';
			}
			else
			{
				$tags_string .= $data[$i]['tag'];
			}
		}
		return $tags_string;
	}

	// one string of tags by picture id
	public function selectTagsInStringByPicID ($picture_id)
	{
		$data = $this->selectTagsByPicID($picture_id);

		$tags_string = '';
		$count = sizeof($data);

		for ($i = 0; $i < $count; $i++)
		{
			if ($i != $count - 1)
			{
				$tags_string .= $data[$i]['tag'] . ', ';
			}
			else
			{
				$tags_string .= $data[$i]['tag'];
			}
		}
		return $tags_string;
	}

	public function addTag (Gallery $gallery_model, $tag, $pictures)
	{
		$data = $this->database->selectOne("SELECT COUNT(*) AS `count` FROM `tbl_tags` WHERE `tag` = ?", array($tag));
		//echo '<pre>'; print_r($data); exit();

		if ($data['count'] == 0)
		{
			$this->database->beginTransaction();

				$tag_id = $this->database->execute("INSERT INTO `tbl_tags` (`tag`) VALUES (?)", array($tag));
				$this->setLastModifyDate();

				foreach ($pictures as $picture)
				{
					$pic = $this->database->selectOne("SELECT `id` FROM `tbl_pictures` WHERE `title` = ?", array($picture));
					$this->database->execute("INSERT INTO `tbl_pictures_tags` (`pictures_id`, `tags_id`) VALUES (?, ?)", array($pic['id'], $tag_id));
				}

				if ($pictures)
				{
					$gallery_model->setLastModifyDate();
				}

			$this->database->commit();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function UpdateTag (Gallery $gallery_model, $tag_id, $tag, $pictures)
	{
		$data = $this->database->selectOne("SELECT COUNT(*) AS `count` FROM `tbl_tags` WHERE `tag` = ?", array($tag));

		//TODO: изменение имени тега выдает оштбку
		if ($data['count'] !== 0)
		{
			$this->database->beginTransaction();

				//$data = $this->database->selectOne("SELECT * FROM `tbl_tags` WHERE `id` = ?", array($tag_id));
				$this->database->execute("UPDATE `tbl_tags` SET `tag` = ? WHERE `id` = ?", array($tag ,$tag_id));
				$this->database->execute("DELETE FROM `tbl_pictures_tags` WHERE `tags_id` = ?", array($tag_id));
				$this->setLastModifyDate();

				foreach ($pictures as $picture)
				{
					$pic = $this->database->selectOne("SELECT `id` FROM `tbl_pictures` WHERE `title` = ?", array($picture));
					$this->database->execute("INSERT INTO `tbl_pictures_tags` (`pictures_id`, `tags_id`) VALUES (?, ?)", array($pic['id'], $tag_id));
				}

				if ($pictures)
				{
					$gallery_model->setLastModifyDate();
				}

			$this->database->commit();
		}
		else
		{
			throw new \LogicException('Tag not exist', 1);
		}
	}

	public function deleteTag (Gallery $gallery_model, $id)
	{
		$this->database->beginTransaction();

			$this->database->execute("DELETE FROM `tbl_tags` WHERE `id` = ?", array($id));
			$this->setLastModifyDate();

			$deleted_pic = $this->database->execute("DELETE FROM `tbl_pictures_tags` WHERE `tags_id` = ?", array($id));

			if ((int)$deleted_pic > 0)
			{
				$gallery_model->setLastModifyDate();
			}

		$this->database->commit();
	}

	public function tagClass ($count)
	{
		switch ($count)
		{
			case 0:
				$result = 'tag0'; break;
			case 1: case 2:
				$result = 'tag1'; break;
			case 3: case 4:
				$result = 'tag2'; break;
			case 5: case 6:
				$result = 'tag3'; break;
			case 7: case 8:
				$result = 'tag4'; break;
			case 9: case 10:
				$result = 'tag5'; break;
			default:
				$result = 'tag6'; break;
		}
		return $result;
	}

	// Устанавливаем время последнего изменения таблицы
	public function setLastModifyDate ()
	{
		return $this->database->execute("UPDATE `tbl_last_modify` SET `modify_date` = ? WHERE `table` = 'tbl_pictures'", array(time()));
	}

	// Получаем время последнего изменения таблицы
	public function getLastModifyDate ()
	{
		$data = $this->database->selectOne("SELECT `modify_date` FROM `tbl_last_modify` WHERE `table` = 'tbl_pictures'");
		return $modify_date = \DateTime::createFromFormat('U', $data['modify_date']);
	}
}