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
        $rooms = Room::where('agent_id', Auth::user()->agent->id)
            ->with('primaryImage')
            ->paginate(10);

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

        $room = Room::create([
            'agent_id' => Auth::user()->agent->id,
            'room_name' => $request->room_name,
            'price_per_night' => $request->price_per_night,
            'max_guests' => $request->max_guests,
            'ac' => $request->has('ac'),
            'tv' => $request->has('tv'),
            'breakfast' => $request->has('breakfast'),
            'attached_bathroom' => $request->has('attached_bathroom'),
            'availability' => $request->availability,
        ]);

        if ($request->hasFile('images')) {
            $order = 0;
            foreach ($request->file('images') as $image) {
                $path = $image->store('room-images/' . $room->id, 'public');

                RoomImage::create([
                    'room_id' => $room->id,
                    'image_path' => $path,
                    'is_primary' => $order === 0,
                    'display_order' => $order++
                ]);
            }
        }

        return redirect()->route('agent.rooms.index')->with('success', 'Room added successfully');
    }

    public function edit(Room $room)
    {
        if ($room->agent_id !== Auth::user()->agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $room->load('images');
        return view('agent.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        if ($room->agent_id !== Auth::user()->agent->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'room_name' => 'required',
            'price_per_night' => 'required|numeric',
            'max_guests' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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
        if ($room->agent_id !== Auth::user()->agent->id) {
            abort(403, 'Unauthorized action.');
        }

        foreach ($room->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $room->delete();

        return back()->with('success', 'Room deleted successfully');
    }

    public function destroyImage(Room $room, RoomImage $image)
    {
        if ($room->agent_id !== Auth::user()->agent->id || $image->room_id !== $room->id) {
            abort(403, 'Unauthorized action.');
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        if ($image->is_primary) {
            $newPrimary = $room->images()->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Image deleted successfully!');
    }

    public function setPrimaryImage(Room $room, RoomImage $image)
    {
        if ($room->agent_id !== Auth::user()->agent->id || $image->room_id !== $room->id) {
            abort(403, 'Unauthorized action.');
        }

        $room->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated successfully!');
    }
}
