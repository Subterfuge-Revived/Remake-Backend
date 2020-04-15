## [Unreleased] 2020-04-15
Re-implementation of the back-end in Laravel.
### Added
 - There is now a unique constraint on player names. This is to avoid ambiguity when logging in using only username and password.
 - With the `new_group` request type, new rooms can be created. Use a `participants[]` field to list the players that should be in the group.
 - Getting a list of blocked players is now possible with the `get_blocks` request type.
 - Added CHANGELOG
 
### Changed
 - Responses can now have different response codes:
   - `200` for all successful responses that have a body
   - `201` for most newly created resources (events, messages, groups, joining a group, blocks)
   - `204` for successfully processed requests that yield no body (deletes)
   - `401` for when the user is unauthorized (invalid or no token)
   - `404` when a resource is requested that does not exist (wrong id)
   - `422` for validation errors or bad requests
   - `500` for internal server errors
 - Responses no longer have a `success` field; this is implied by a `2XX` response code.
 - Token generation rewrite (and stored in hashed form) but should work functionally identical to before.
 - Rework of the messaging system to use groups rather than only one-on-one messaging.
 - No separate tables for open and ongoing rooms. Instead, we have `started_at` and `closed_at` fields.
 - When fetching events from a room, it is no longer necessary for the room to be ongoing. Instead, for rooms that haven't yet started, an empty list of events is returned.
 - When a room is deleted, an empty `204 No Content` response is returned instead of returning an id that is no longer in use.
 - When calling `get_room_data`, closed rooms are not shown by default. This can be overridden by including a parameter `return_closed_rooms` with value `true`.
