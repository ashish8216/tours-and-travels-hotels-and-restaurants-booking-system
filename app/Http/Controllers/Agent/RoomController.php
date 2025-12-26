<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::where('agent_id', Auth::user()->id)->with('primaryImage')->paginate(10);
        return view('agent.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('agent.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required',
            'price_per_night' => 'required|numeric',
            'max_guests' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create room
        $room = Room::create([
            'agent_id' => Auth::user()->id,
            'room_name' => $request->room_name,
            'price_per_night' => $request->price_per_night,
            'max_guests' => $request->max_guests,
            'ac' => $request->has('ac'),
            'tv' => $request->has('tv'),
            'breakfast' => $request->has('breakfast'),
            'attached_bathroom' => $request->has('attached_bathroom'),
            'availability' => $request->availability,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $order = 0;
            foreach ($request->file('images') as $image) {
                $path = $image->store('room-images/' . $room->id, 'public');

                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $path,
                    'is_primary' => $order === 0, // First image is primary
                    'display_order' => $order++
                ]);
            }
        }

        return redirect()->route('agent.rooms.index')->with('success', 'Room added successfully');
    }

    public function edit(Room $room)
    {
        // Check authorization - ensure agent can only edit their own rooms
        if ($room->agent_id !== Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $room->load('images'); // Load images relationship
        return view('agent.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        // Check authorization
        if ($room->agent_id !== Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'room_name' => 'required',
            'price_per_night' => 'required|numeric',
            'max_guests' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update room
        $room->update([
            'room_name' => $request->room_name,
            'price_per_night' => $request->price_per_night,
            'max_guests' => $request->max_guests,
            'ac' => $request->has('ac'),
            'tv' => $request->has('tv'),
            'breakfast' => $request->has('breakfast'),
            'attached_bathroom' => $request->has('attached_bathroom'),
            'availability' => $request->availability,
        ]);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $existingCount = $room->images()->count();
            $order = $existingCount;

            foreach ($request->file('images') as $image) {
                $path = $image->store('room-images/' . $room->id, 'public');

                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $path,
                    'is_primary' => $existingCount === 0 && $order === 0,
                    'display_order' => $order++
                ]);
            }
        }

        return redirect()->route('agent.rooms.index')->with('success', 'Room updated successfully');
    }

    public function destroy(Room $room)
    {
        // Check authorization
        if ($room->agent_id !== Auth::user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete all room images from storage
        foreach ($room->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete the room (this will cascade delete images due to foreign key constraint)
        $room->delete();

        return back()->with('success', 'Room deleted successfully');
    }

    // New method: Delete a single image
    public function destroyImage(Room $room, RoomImage $image)
    {
        // Check authorization
        if ($room->agent_id !== Auth::user()->id || $image->room_id !== $room->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the file from storage
        Storage::disk('public')->delete($image->image_path);

        // Delete the image record
        $image->delete();

        // If the deleted image was primary, make another image primary
        if ($image->is_primary) {
            $newPrimary = $room->images()->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Image deleted successfully!');
    }

    // New method: Set image as primary
    public function setPrimaryImage(Room $room, RoomImage $image)
    {
        // Check authorization
        if ($room->agent_id !== Auth::user()->id || $image->room_id !== $room->id) {
            abort(403, 'Unauthorized action.');
        }

        // Set all images to not primary
        $room->images()->update(['is_primary' => false]);

        // Set selected image as primary
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated successfully!');
    }
}
