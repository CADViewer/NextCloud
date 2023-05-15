<?php
/**
 * @copyright Copyright (c) 2019 John Molakvoæ <skjnldsv@protonmail.com>
 *
 * @author John Molakvoæ <skjnldsv@protonmail.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

return [
	'routes' => [
        ['name' => 'cadviewer#path','url'  => '/ajax/cadviewer.php','verb' => 'POST'],
        ["name" => "cadviewer#move_pdf", "url" => "/ajax/cadviewer/move-pdf", "verb" => "POST"],
        ["name" => "cadviewer#flush_cache", "url" => "/ajax/cadviewer/flush-cache", "verb" => "POST"],
		['name' => 'cadviewer#ping', 'url' => '/ajax/cadviewer/ping','verb' => 'GET'],
        ["name" => "settings#doctor", "url" => "/ajax/settings/doctor", "verb" => "POST"],
        ["name" => "settings#save_common", "url" => "/ajax/settings/common", "verb" => "POST"],
        ["name" => "settings#save_parameters", "url" => "/ajax/settings/parameters", "verb" => "PUT"],
        ["name" => "settings#save_frontend_parameters", "url" => "/ajax/settings/frontend_parameters", "verb" => "PUT"],
        ["name" => "settings#save_skin", "url" => "/ajax/settings/skin", "verb" => "PUT"],
        ["name" => "settings#check_auto_exchange_licence_key", "url" => "/ajax/settings/autoexchange-verify", "verb" => "GET"],
        ["name" => "settings#save_axlic_file", "url" => "/ajax/settings/autoexchange-save-axlic", "verb" => "POST"],
        ["name" => "settings#save_shx_file", "url" => "/ajax/settings/shx-file", "verb" => "POST"],
        ["name" => "settings#display_log", "url" => "/ajax/settings/log", "verb" => "POST"],
        ["name" => "settings#save_ax_font_map", "url" => "/ajax/settings/save-font-map", "verb" => "POST"],
	]
];
