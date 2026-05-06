<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedAt($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $chatable_type
 * @property int $chatable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $chatable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereChatableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereChatableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Chat whereUpdatedAt($value)
 */
	class Chat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $path
 * @property string $name
 * @property string|null $fileable_type
 * @property int|null $fileable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $fileable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereUpdatedAt($value)
 */
	class File extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereUpdatedAt($value)
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $chat_id
 * @property int $user_id
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chat $chat
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUserId($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $thesis_id
 * @property int|null $section_id
 * @property \Illuminate\Support\Carbon|null $date
 * @property string|null $start_time
 * @property int|null $duration
 * @property string|null $end_time
 * @property string $event_type
 * @property string|null $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Section|null $section
 * @property-read \App\Models\Thesis $thesis
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereEventType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereThesisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schedule whereUpdatedAt($value)
 */
	class Schedule extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \App\Enums\SectionStatus $status
 * @property string|null $description
 * @property string|null $full_description
 * @property string|null $chairs
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Location> $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Thesis> $theses
 * @property-read int|null $theses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\SectionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereChairs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereFullDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereUpdatedAt($value)
 */
	class Section extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $slug
 * @property string|null $title
 * @property string|null $markdown_content
 * @property array<array-key, mixed>|null $files
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $html_content
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticPage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticPage whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticPage whereMarkdownContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaticPage whereUpdatedAt($value)
 */
	class StaticPage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Thesis> $theses
 * @property-read int|null $theses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Status whereUpdatedAt($value)
 */
	class Status extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $co_authors
 * @property int $user_id
 * @property int $status_id
 * @property int $section_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chat|null $chat
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Schedule|null $schedule
 * @property-read \App\Models\Section $section
 * @property-read \App\Models\Status $status
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ThesisFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis whereCoAuthors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Thesis whereUserId($value)
 */
	class Thesis extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $vk_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $patronymic
 * @property string|null $avatar
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $full_name
 * @property mixed $role_id
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Section> $sections
 * @property-read int|null $sections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Thesis> $theses
 * @property-read int|null $theses_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

