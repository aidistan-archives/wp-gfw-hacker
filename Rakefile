require 'fileutils'

EXCLUDE = %w(
  README.md
  LICENSE
  Rakefile
  .gitignore
)

desc 'Build a .zip file for uploading'
task :default do
  # Prepare
  FileUtils.rm_r('insideGFW') if Dir.exist?('insideGFW')
  FileUtils.rm('insideGFW.zip') if File.exist?('insideGFW.zip')

  # Build
  (`git ls-files`.split("\n") - EXCLUDE).each do |src|
    next unless File.exist?(src)
    dst = 'insideGFW/' + src
    FileUtils.mkdir_p(File.dirname(dst)) unless Dir.exist?(File.dirname(dst))
    FileUtils.cp(src, dst)
  end

  `zip -r insideGFW insideGFW`

  # Clean up
  FileUtils.rm_r('insideGFW') if Dir.exist?('insideGFW')
end
