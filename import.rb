# encoding: utf-8

require 'RMagick'
require 'fileutils'

puts "\nCreating array...\n"

codes = ''

File.open('emoji-cheat-sheet.com/public/index.html').lines.each do |line|

  if line.match(/graphics\/emojis.*:.*name/)
    image = line.match(/src=".*?"/).to_s.gsub('src="', '').gsub('"', '')
    code = line.match(/span.*?</).to_s.match(/\>.*\</).to_s.gsub('<', '').gsub('>', '')
    codes += "    '#{code}' => '#{image.gsub('graphics/emojis/', '')}',\n"
  end

end

content = "<?php

class Emoji_Emoticons_Codes {

  public static $codes = array(
{codes}
  );

}"

File.open('emoji_emoticons-codes.php', 'w') { |f| f.write content.gsub('{codes}', codes) }

puts "\nDone!\n"

puts "\nResizing mojis...\n"

emojis = 'emoji-cheat-sheet.com/public/graphics/emojis/'

FileUtils.rm_rf 'emojis/' if Dir.exists? 'emojis/'

FileUtils.mkdir 'emojis'

Dir.entries(emojis).each do |emoji|

  next if emoji == '.' or emoji == '..'

  FileUtils.cp(emojis + emoji, 'emojis/' + emoji)

  Magick::Image.read('emojis/' + emoji)[0].resize_to_fill(20, 20).write('emojis/' + emoji)

  print '<3 '

end
puts "\n"

FileUtils.cp('emoji-cheat-sheet.com/LICENSE', 'emojis/LICENSE')

puts "\nDone!\n"